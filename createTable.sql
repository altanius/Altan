create table user
	(username varchar(15), 
	password varchar(15), 
	name varchar(15) NOT NULL,
	surname varchar(15) NOT NULL,
	phone varchar(11) NOT NULL ,
	type varchar(15)
	check (type in ('Client','Owner')),
	primary key (username)
);

create table districts
(district_name varchar(20) NOT NULL,
primary key(district_name)
);

create table address
  (username varchar(15),
    door_no varchar(4),
    street varchar(20),
    district varchar(20) NOT NULL, 
    city varchar(20),
    primary key(username,door_no,street,district),
    foreign key (username) references user(username)
     on delete cascade,
   foreign key (district) references districts(district_name)
    on delete cascade
  );  

create table cart
  (uname varchar(15),
    id INTEGER AUTO_INCREMENT,
    cost numeric(5,2) NOT NULL,
    primary key (id),
    foreign key (uname) references user(username)
     on delete cascade
   );

create table restaurant
 (owner varchar(15),
   name varchar(20),
  menu_id INTEGER AUTO_INCREMENT UNIQUE, 
   primary key (name),
   foreign key (owner) references user(username)
    on delete cascade
   );

create table item
  (item_id INTEGER AUTO_INCREMENT,
    menu_id INTEGER,
    name varchar(20),
    price numeric(5,2),
    type varchar(20) NOT NULL
    check(type in ('Soup', 'Appetizer', 'Salad', 'Main Dish', 'Fish', 'Side', 'Drink')),
    primary key (item_id),
    foreign key (menu_id) references restaurant(menu_id)
     on delete cascade
    );

create table serves_to
  (rest_name varchar(20) NOT NULL,
    district varchar(20),
    duration int 
    check(duration>0 and duration<120),
    foreign key (rest_name) references restaurant(name)
     on delete cascade,
    foreign key (district) references districts(district_name)
     on delete set null
    );

create table rank
  (rest_name varchar(20),
    rank int NOT NULL
    check(rank>0 and rank<10),
foreign key (rest_name) references restaurant(name)
	on delete cascade
    );

create table comments
  (rest_name varchar(20),
    comments varchar(50) NOT NULL,
    foreign key (rest_name) references restaurant(name)
     on delete cascade 
    );

create table orders
  (id INTEGER AUTO_INCREMENT,
  client_name varchar(15),
  rest_name varchar(20),
  cost numeric(5,2) NOT NULL,
  date TIMESTAMP NOT NULL,
  is_delivered varchar(5) NOT NULL,
  check(is_delivered in ('Yes','No')),
  primary key (id),
  foreign key (client_name) references user(username)
    on delete set null,
  foreign key (rest_name) references restaurant(name)
   on delete set null
 ); 



