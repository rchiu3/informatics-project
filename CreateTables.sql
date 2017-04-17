-- Drop existing tables if they exist so we can create empty tables --
DROP TABLE IF EXISTS OrderLine;
DROP TABLE IF EXISTS Order_T;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Employee;
DROP TABLE IF EXISTS Store;
DROP TABLE IF EXISTS Payment;

-- Customer Table --

CREATE TABLE Customer (
	CustomerID int unsigned NOT NULL AUTO_INCREMENT,
	CutomerName varchar(30) NOT NULL,
	Address varchar(200) NOT NULL,
	Email varchar(60) NOT NULL,
	Username varchar(30),
	HashedPass varchar(130),
	PRIMARY KEY (CustomerID)
);
	
	-- Should we include payment info in customer table if so what is needed? --
	-- Credit Card Number, Name on Card, Billing Address, CSV, Expiration Date, Card Type --

-- Order Table --

CREATE TABLE Order_T (
    OrderID int unsigned NOT NULL AUTO_INCREMENT,
    OrderDate DATETIME DEFAULT NULL,
    Paid BOOLEAN,
    CustomerID int unsigned NOT NULL,
    DeliveryDate DATE NOT NULL,
    DeliveryAddress varchar (150) NOT NULL,
    OrderStatus varchar(50),
    PRIMARY KEY (OrderID),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

-- Store Table --

CREATE TABLE Store (
    StoreID int unsigned NOT NULL AUTO_INCREMENT,
    StoreName varchar(50) NOT NULL, 
    PRIMARY KEY (StoreID)
);

-- Sample store data --

INSERT INTO Store(StoreName) VALUES ("John's Grocery");
INSERT INTO Store(StoreName) VALUES ("Walmart");
INSERT INTO Store(StoreName) VALUES ("Hyvee");
 
-- Category Table --

CREATE TABLE Category (
    CategoryID int unsigned NOT NULL AUTO_INCREMENT,
    CategoryName varchar(30),
    PRIMARY KEY (CategoryID)
);

-- Sample category data --

INSERT INTO Category(CategoryName) VALUES ('Dairy');
INSERT INTO Category(CategoryName) VALUES ('Produce');
INSERT INTO Category(CategoryName) VALUES ('Bakery');
INSERT INTO Category(CategoryName) VALUES ('Frozen Foods');
INSERT INTO Category(CategoryName) VALUES ('Meat');
INSERT INTO Category(CategoryName) VALUES ('Seafood');
INSERT INTO Category(CategoryName) VALUES ('Beverages');
INSERT INTO Category(CategoryName) VALUES ('Grains & Pasta');

-- Product Table --

CREATE TABLE Product (
    ProductID int unsigned NOT NULL AUTO_INCREMENT,
    ProductName varchar(50) NOT NULL,
    Price DECIMAL(5,2) NOT NULL,
    CategoryID int unsigned NOT NULL,
    Inventory INT NOT NULL,
    StoreID int unsigned NOT NULL,
    Picture varchar(128),
    PRIMARY KEY (ProductID),
    FOREIGN KEY (StoreID) REFERENCES Store(StoreID),
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID)
);

-- Sample product data --
-- John's Grocery's products (StoreID = 1) --
-- Dairy CatID = 1 --
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('2% Milk - 1 Gallon', 4.99, 1, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Grade A Large Eggs - 12 ct', 1.99, 1, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('1% Milk - 1 Gallon', 4.99, 1, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Grade A Large Eggs - 18 ct', 2.49, 1, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Butter - 16 oz', 1.99, 1, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('American Cheese - 12 oz Pack', 2.89, 1, 100, 1);
-- Produce CatID = 2 --
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Bananas - .5 lbs', .89, 2, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Lemons - 1 ea', .99, 2, 200, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Green Bell Pepper - 1 ea', .33, 2, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('HoneyCrisp Apples - .45 lbs', 2.49, 2, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Roma Tomatoes - .25 lbs', .99, 2, 100, 1);
INSERT INTO Product(ProductName, Price, CategoryID, Inventory, StoreID) VALUES ('Blueberries - 6 oz Pack', 4.29, 2, 100, 1);
-- Bakery CatID = 3 --
-- Walmart's products (StoreID = 2)--
-- Hyvee's products (StoreID = 3 --
-- OrderLine Table --

CREATE TABLE OrderLine (
    OrderLineID int unsigned NOT NULL AUTO_INCREMENT,
    Quantity int unsigned NOT NULL,
    OrderID int unsigned NOT NULL,
    ProductID int unsigned NOT NULL, 
    PRIMARY KEY (OrderLineID),
    FOREIGN KEY (OrderID) REFERENCES Order_T(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Employee Table --

CREATE TABLE Employee (
    EmployeeID int unsigned NOT NULL AUTO_INCREMENT,
    EmployeeName varchar(50) NOT NULL,
    EmployeeAdmin BOOLEAN NOT NULL,
    EmployeeEmail varchar(100) NOT NULL,
    EmployeePass varchar(300) NOT NULL,
    StoreID int unsigned NOT NULL,
    PRIMARY KEY (EmployeeID),
    FOREIGN KEY (StoreID) REFERENCES Store(StoreID)
);

-- Payment Table --
-- Not sure how/or if we should have foreign keys in this (CustomerID) only question is what will we do with guests?? --

CREATE TABLE Payment (
	PaymentID int unsigned NOT NULL AUTO_INCREMENT,
	CreditCardNumber varchar(30) NOT NULL,
	CardName varchar(50) NOT NULL,
	BillingAddress varchar(200) NOT NULL,
	CSV int(10) NOT NULL,
	ExpirationDate varchar(10) NOT NULL,
	CardType varchar(20) NOT NULL,
	PRIMARY KEY (PaymentID),
	
);
	