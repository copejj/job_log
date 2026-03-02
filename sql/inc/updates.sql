insert into labels (key, label)
values ('job_link', 'Job Link'), ('description', 'Job Description') on conflict do nothing;
