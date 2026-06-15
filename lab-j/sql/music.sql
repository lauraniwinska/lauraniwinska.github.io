create table if not exists music
(
    id      integer not null
    constraint music_pk
    primary key autoincrement,
    subject text not null,
    content text not null
);
