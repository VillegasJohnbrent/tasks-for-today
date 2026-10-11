# TSA2 verification checklist

Run these checks locally and repeat the essential flows on the hosted site.
These are expected results, not a claim that they have already been executed.

## Logged out

1. Open /, /tasks, /profile, and /about. All four must load without login.
2. Open /tasks/new. It must redirect to /login.
3. Open /tasks/1/edit (or use another active task ID). It must redirect to /login.
4. Management buttons must be hidden on public task lists.
5. Enter a wrong password on /login. It must show an error and keep you logged out.
6. Direct POST attempts to /tasks, /tasks/1/update, and /tasks/1/delete must
   redirect to /login, without changing any record. The optional smoke test
   sends these requests without a CSRF token to verify auth runs first.

## Logged in

7. Log in with demo_user / Tsa2Demo123! (or your configured password).
8. Task List must show New Task, Edit, and Delete; the header must show Log out.
9. Create a task titled "TSA2 verification task" with today's date and pending.
   It must appear in Task List and Welcome. Record its numeric ID.
10. Create another task dated tomorrow. It must appear in Task List but not Welcome.
11. Edit the first task: change title and status to completed. Confirm the row,
    dashboard completed count, and database reflect the changes.
12. Submit a title consisting only of spaces. Server validation must reject it.
13. For a server validation check, use browser developer tools to remove a
    required attribute, then submit an empty title or date. Errors must display
    and nothing may be saved. Also test an invalid date/status through the
    optional smoke test, which bypasses browser validation.
14. Click Delete on the first test task and confirm. It must disappear from
    both Task List and Welcome.
15. In phpMyAdmin, replace 10 below with the recorded test task ID and run:

```sql
SELECT id, title, is_archived FROM tasks WHERE id = 10;
```

The original row must remain, with is_archived = 1. It must not be physically deleted.
16. Open /tasks/{archived-id}/edit. It must show not found. Archived tasks must
    not be editable through a direct URL.
17. Inspect users.password in phpMyAdmin: it must contain a password hash,
    not Tsa2Demo123! or your plaintext password. The Profile must not expose it.
18. Click Log out. Revisit /tasks/new: it must redirect to /login again.
    The four public pages must still work.

## Database and submission

```sql
SELECT COUNT(*) AS total_users FROM users;
SELECT task_date, COUNT(*) AS tasks FROM tasks GROUP BY task_date ORDER BY task_date;
SELECT id, title, is_archived FROM tasks ORDER BY id;
```

The original project has exactly one demo user. The tasks retain at least
8 records spanning 3 dates; additional manual test tasks may increase the count.

- About and README identify the correct developer and section.
- GitHub contains raw project files plus TSA2 migration and seeder.
- Hosted links for all four public pages work in a private browser window.
- Hosted login, create, edit, archive, and logout work.
- Submit both the repository URL and hosted application URL.
