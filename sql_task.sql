/* 1) Get all statuses, not repeating, alphabetically ordered */
SELECT DISTINCT status FROM task ORDER BY status ASC;

/* 2) Get the count of all tasks in each project, order by tasks count descending */
SELECT p.name as project_name, COUNT(t.project_id) as task_number
FROM task as t
JOIN project as p ON p.id = t.project_id
GROUP BY t.project_id
ORDER BY task_number DESC;

/* 3) Get the count of all tasks in each project, order by projects names */
SELECT p.name as project_name, COUNT(t.project_id) as task_number
FROM task as t
JOIN project as p ON p.id = t.project_id
GROUP BY t.project_id
ORDER BY project_name ASC;

/* 4) Get the tasks for all projects having the name beginning with "N" letter */
SELECT p.name as project_name, t.name as task_name
FROM task as t
JOIN project as p ON p.id = t.project_id
WHERE p.name LIKE 'N%';

/* 5) Get the list of all projects containing the 'a' letter in the middle of the name, and show the tasks count near each project. Mention that there can exist projects without tasks and tasks with project_id = NULL */
SELECT p.name as project_name, IFNULL(COUNT(t.project_id), 0) as task_number
FROM project as p
LEFT JOIN task as t ON t.project_id = p.id
WHERE p.name LIKE '%a%'
GROUP BY t.project_id;

/* 6) Get the list of tasks with duplicate names. Order alphabetically */
SELECT name
FROM task
GROUP BY name
HAVING COUNT(*) > 1
ORDER BY name ASC;

/* 7) Get list of tasks having several exact matches of both name and status, from the project 'Garage'. Order by matches count */
SELECT name
FROM task
WHERE project_id IN (SELECT id FROM project WHERE name = 'Garage')
GROUP BY name, status
HAVING COUNT(*) > 1
ORDER BY COUNT(*) DESC;

/* 8) Get the list of project names having more than 10 tasks in status 'completed'. Order by project_id */
SELECT name
FROM project
WHERE id IN (SELECT project_id FROM task WHERE status = 'completed' GROUP BY project_id HAVING COUNT(*) > 10)
ORDER BY id ASC;