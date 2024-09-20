create table tasks
(
    id          int auto_increment
        primary key,
    title       varchar(255)                           not null,
    author      varchar(255)                           not null,
    date        datetime     default CURRENT_TIMESTAMP null,
    description text                                   null,
    status      varchar(255) default 'pending'         null
);

create index idx_title
    on tasks (title);


