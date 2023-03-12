
create table if not exists clients
(
    id int constraint _clients_id_index primary key,
    /* composite index, phone, salary? */
    firstname varchar(45) null ,
    lastname varchar(45) null ,
    birthday date not null ,
    phone varchar(45) not null ,
    mail varchar(45) null ,
    address varchar(45) null ,
    salary numeric(15,2) null ,
    currency char(3) null,
    created_at timestamp default now()
);


/*
create table if not exists client_salary
(
    id int constraint _salary_index primary key,
    client_id int not null references clients(id),
    salary numeric(15,2) null ,
    currency char(3) null,
    created_at timestamp not null
);
*/

create table if not exists credit_limits_history
(
    id serial primary key,
    _ref uuid constraint _requested_ref_index default gen_random_uuid(),
    client_id int not null references clients(id),
    requested_credit_limit numeric(15,2) not null,
    actual_credit_limit numeric(15,2) not null,
    resolution bool not null
);

/*
CREATE SEQUENCE requested_credit_limits_history_id_seq;
ALTER SEQUENCE requested_credit_limits_history_id_seq
    OWNED BY requested_credit_limits_history.id
)
 */
SELECT
    tablename,
    indexname,
    indexdef
FROM
    pg_indexes
WHERE
        schemaname = 'public'
ORDER BY
    tablename,
    indexname;