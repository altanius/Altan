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
(username varchar(15)  ,
door_no int NOT NULL,
street varchar(20) NOT NULL,
district varchar(20) NOT NULL,
primary key (door_no, street, district),
foreign key (district) references districts(district_name)
	on delete cascade,
foreign key (username) references user(username)
	on delete set null
);


create table restaurant
(name varchar(15) NOT NULL,
district varchar(20) NOT NULL,
menu_id int UNIQUE,
primary key (name, district),
foreign key (district) references districts(district_name)
	on delete cascade
);

create table cart
(cart_id INTEGER PRIMARY KEY AUTOINCREMENT,
username varchar(15), 
cost numeric(5,2) NOT NULL,
primary key (cart_id),
foreign key (username) references user(username)
	on delete cascade
);

create table item
(item_id int,
menu_id int,
name varchar(20) NOT NULL,
price numeric(4,2) DEFAULT (0),
type varchar(20) NOT NULL
check(type in ('Soup', 'Appetizer', 'Salad', 'Main Dish', 'Side',
	'Dessert', 'Drink'),
primary key (item_id, menu_id),
foreign key (menu_id) references restaurant(menu_id)
	on delete cascade
);

create table includes
(cart_id int,
item_id int,
menu_id int,
foreign key (cart_id) references cart(cart_id)
	on delete cascade,
foreign key (item_id, menu_id) references item(item_id, menu_id)
	on delete set null
);

create table has
(username varchar(15),
cart_id int,
foreign key (username) references user(username)
	on delete cascade,
foreign key (cart_id) references cart(cart_id)
	on delete set null
);

create table owns
(owner_name varchar(15) NOT NULL,
rest_name varchar(15) NOT NULL,
foreign key (owner_name) references user(username)
	on delete cascade,
foreign key (rest_name) references restaurant(name)
	on delete set null
);

create table serves_to
(district_name varchar(20) NOT NULL,
rest_name varchar(15) NOT NULL,
foreign key(district_name) references districts(district_name)
	on delete set null
foreign key(rest_name) references restaurant(name)
	on delete cascade
);


