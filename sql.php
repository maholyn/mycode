<?php
require("connection.php"); 
$sqlCommand = "CREATE TABLE IF NOT EXISTS product( 
	product_id int(10) NOT NULL AUTO_INCREMENT, 
	product_name varchar(25) NOT NULL, 
	price decimal(6,2) NOT NULL, 
	description varchar(250) NOT NULL, 
	category varchar(20) NOT NULL, 
	size varchar(10) NOT NULL, 
	stock int(5) NOT NULL, PRIMARY KEY (product_id), 
	UNIQUE KEY product_name (product_name) 
	) ENGINE = InnoDB";

$sqlCommand = "INSERT INTO product (product_id, product_name, price, description, category, size, product_image, stock) VALUES
	(1, 'AZUMAICHI', '38.99', 'Silky, mellow, soft, slight aroma of rice, rounded texture with clean finish.',
		'Junmai', '720 ml', 30),
	(2, 'BENI MANSAKU', '52.99', 'Slightly floral with light flavor. Soft and delicate texture.',
		'Junmai Ginjo', '720 ml', 25),
	(3, 'KAKUREI', '72.99', 'Aromatic, full-bodied, hints of pear and apple. Clean finish.',
		'Daiginjo', '720 ml', 42),
	(4, 'KUBOTA MANJU', '99.99', 'Soft, round, elegant, and clean with a quick finish. Very well balanced with a touch of richness.',
		'Junmai Daiginjo', '720 ml', 8),
	(5, 'WATARI BUNE 55', '23.99', 'Rich and deep notes of honeydew and pineapple, nutty and earthy flavor. Unique rice, only sake that uses it.',
		'Junmai Ginjo', '300 ml', 57),
	(6, 'FUKUJU', '39.99', 'Hint of strawberry and tropical fruit aromas with subtle flavors of melon, citrus, and mango that unfold gracefully on the palate.',
		'Junmai Ginjo', '720 ml', 19)
	";
?>