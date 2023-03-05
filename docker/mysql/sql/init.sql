
create database if not exists 'pbt_db';

use 'pbt_db';

start transaction;

create table 'messages' (
    'id' int unsigned not null,
    'message' varchar(255) not null
) engine=InnoDB default charset='utf-8';

insert into 'messages'
('id', 'message')
values
('1', 'foo'),
('2', 'bar'),
('3', 'baz');

alter table 'messages' add primary key ('id');

commit;