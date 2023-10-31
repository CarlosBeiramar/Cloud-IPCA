create database if not exists cloud;
use cloud;
create table `user` (
iduser bigint not null auto_increment,
`name` varchar(255) null,
`email` varchar(200) not null,
`password` varchar(255) not null,
`apikey` varchar(255) not null,
`type` tinyint not null DEFAULT 1 comment '0=>admin,1=>normal user',
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null ,
`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  not null ON UPDATE CURRENT_TIMESTAMP ,
primary key (iduser),
unique (email)
);
alter table user  add unique index apikey_unique (apikey);
INSERT INTO `user` VALUES (1,'teste','a6573@alunos.ipca.pt','SlZ1QXBYZEcrY0FGY21mWDNUT2pQdz09','sdfsdfsd',0,'2023-10-28 18:51:49','2023-10-28 18:53:02');

create table `uc` (
iduc bigint not null auto_increment,
`code` varchar(20) not null,
`name` varchar(255) not null,
`description` varchar(255)   null, 
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null ,
`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  not null ON UPDATE CURRENT_TIMESTAMP ,
 primary key (iduc),
 unique (code)
);

create table `user_uc` (
iduc bigint not null, 
`iduser` bigint not null, 
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null ,
`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  not null ON UPDATE CURRENT_TIMESTAMP 
, primary key (iduc,iduser),
FOREIGN KEY (iduser) REFERENCES user(iduser),
FOREIGN KEY (iduc) REFERENCES uc(iduc)
);



create table `logs` (
idlog bigint not null auto_increment, 
`iduser` bigint  null, 
`ip` varchar(100)  null,
`resource` varchar(50) not null,
`method` varchar(5) not null,
`success` tinyint not null DEFAULT 0 comment '0 error 1 success',
`tokenvalidate` tinyint not null DEFAULT 0 comment '0 error 1 success',
`message` varchar(1000) not null, 
`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null ,
`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP  not null ON UPDATE CURRENT_TIMESTAMP 
,primary key (idlog),
FOREIGN KEY (iduser) REFERENCES user(iduser)
);

CREATE INDEX logs_ip ON logs (ip);
CREATE INDEX logs_resource ON logs (resource);
CREATE INDEX logs_method ON logs (method);