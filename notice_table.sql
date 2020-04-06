create table ocardb1.oc_notice
(
    id            bigint auto_increment
        primary key,
    title         varchar(255) null,
    summary       text         null,
    content       longtext     null,
    date_added    datetime     null,
    date_modified datetime     null,
    status        int(1)       null
);

