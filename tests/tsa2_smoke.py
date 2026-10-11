"""Optional live HTTP verification. Creates and archives one test task."""
import datetime as dt
import html
import http.cookiejar
import os
import re
import urllib.error
import urllib.parse
import urllib.request
import uuid

BASE = os.environ.get('TSA2_URL', 'http://localhost:8080').rstrip('/')
USER = os.environ.get('TSA2_USERNAME', 'demo_user')
PASSWORD = os.environ.get('TSA2_PASSWORD', 'Tsa2Demo123!')

class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl):
        return None

opener = urllib.request.build_opener(
    urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()), NoRedirect())

def request(path, data=None):
    encoded = urllib.parse.urlencode(data).encode() if data is not None else None
    req = urllib.request.Request(BASE + path, data=encoded)
    try:
        response = opener.open(req, timeout=20)
    except urllib.error.HTTPError as error:
        response = error
    with response:
        return response.code, response.read().decode('utf-8'), response.headers

def check(condition, message):
    if not condition:
        raise AssertionError(message)
    print('PASS:', message)

def token(path):
    code, body, _ = request(path)
    assert code == 200, (path, code)
    match = re.search(r'name="csrf_test_name"\s+value="([^"]+)"', body)
    assert match, 'CSRF field not found on ' + path
    return html.unescape(match.group(1))

def post(path, fields, form):
    return request(path, dict(fields, csrf_test_name=token(form)))

def redirects_to(result, suffix):
    return result[0] in (302, 303) and urllib.parse.urlparse(result[2].get('Location', '')).path.rstrip('/').endswith(suffix)

for route in ['/', '/tasks', '/profile', '/about']:
    check(request(route)[0] == 200, 'Public route works: ' + route)
for route in ['/tasks/new', '/tasks/1/edit']:
    check(redirects_to(request(route), '/login'), 'Logged-out GET redirects: ' + route)
for route in ['/tasks', '/tasks/1/update', '/tasks/1/delete']:
    check(redirects_to(request(route, {}), '/login'), 'Logged-out POST redirects: ' + route)
check(post('/login', {'username': USER, 'password': 'wrong-' + uuid.uuid4().hex}, '/login')[0] == 422,
      'Incorrect password rejected')
check(redirects_to(post('/login', {'username': USER, 'password': PASSWORD}, '/login'), '/tasks'),
      'Correct login accepted')
check(request('/tasks/new')[0] == 200, 'Logged-in creation form works')

now = dt.datetime.now(dt.timezone(dt.timedelta(hours=8))).date()
today = now.isoformat()
tomorrow = (now + dt.timedelta(days=1)).isoformat()
marker = 'TSA2-test-' + uuid.uuid4().hex[:10]
valid = {'title': marker, 'status': 'pending', 'task_date': today}
for field, bad in [('title', ''), ('title', '   '), ('title', 'x' * 151),
                   ('task_date', ''), ('task_date', '2026-02-30'), ('status', 'invalid')]:
    invalid = dict(valid)
    invalid[field] = bad
    check(post('/tasks', invalid, '/tasks/new')[0] == 422,
          'Server rejects invalid ' + field + ': ' + repr(bad[:25]))

request('/tasks', valid)  # Authenticated but deliberately missing CSRF.
check(marker not in request('/tasks')[1], 'Missing CSRF cannot create a task')
check(redirects_to(post('/tasks', valid, '/tasks/new'), '/tasks'), 'Create succeeds')
body = request('/tasks')[1]
row = next(row for row in re.findall(r'<tr>(.*?)</tr>', body, re.S) if marker in row)
match = re.search(r'/tasks/(\d+)/edit', row)
assert match, 'Created task ID missing from Edit link'
task_id = match.group(1)
edit_path = '/tasks/' + task_id + '/edit'
check(marker in request('/')[1], 'Today task appears on Welcome')
invalid_update = dict(valid, title='')
check(post('/tasks/' + task_id + '/update', invalid_update, edit_path)[0] == 422,
      'Invalid update is rejected')
check(marker in request('/tasks')[1], 'Invalid update preserves original title')
changed_title = marker + ' <script>alert(1)</script>'
changed = {'title': changed_title, 'status': 'completed', 'task_date': tomorrow}
check(redirects_to(post('/tasks/' + task_id + '/update', changed, edit_path), '/tasks'), 'Update succeeds')
body = request('/tasks')[1]
check(html.escape(changed_title) in body and changed_title not in body, 'Task title is HTML escaped')
check(marker not in request('/')[1], 'Tomorrow task is excluded from Welcome')
check(request('/tasks/' + task_id + '/delete')[0] in (404, 405), 'GET cannot delete a task')
check(redirects_to(post('/tasks/' + task_id + '/delete', {}, '/tasks'), '/tasks'), 'Archive succeeds')
check(marker not in request('/tasks')[1], 'Archived task excluded from Task List')
check(request(edit_path)[0] == 404, 'Archived task cannot be edited')
check(redirects_to(post('/logout', {}, '/tasks'), '/login'), 'Logout succeeds')
check(redirects_to(request('/tasks/new'), '/login'), 'Management blocked after logout')
print('\nHTTP checks passed. Inspect tasks.id=' + task_id + ' in phpMyAdmin: is_archived must be 1 and the row must still exist.')
