alter table users
add column if not exists is_admin boolean not null default false;

update users
set is_admin = true
where user_id = 1;
