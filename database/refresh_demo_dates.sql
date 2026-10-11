-- Optional: run before a later demonstration if you want sample tasks for today.
-- Preserves IDs, task text, statuses, users, and relative date spacing.
-- This is a manual demo action; the application never changes dates automatically.
SET time_zone = '+08:00';
SET @shift_days = DATEDIFF(CURDATE(), (SELECT MIN(task_date) FROM tasks)) - 1;
UPDATE tasks SET task_date = DATE_ADD(task_date, INTERVAL @shift_days DAY);
