USE udemy;
DROP TABLE myhttptbl;
CREATE TABLE myhttptbl (
    id bigint(20) unsigned NOT NULL,
    capacity INT NULL,
    name varchar(100) NULL,
    changedate datetime,
    PRIMARY KEY (id)
);

USE udemy;
DROP TABLE myappnametbl;
CREATE TABLE myappnametbl (
    appName varchar(100) NULL,
    PRIMARY KEY (appName)
);
