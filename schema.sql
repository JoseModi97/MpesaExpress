CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchant_request_id` varchar(100) NOT NULL,
  `checkout_request_id` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `mpesa_receipt_number` varchar(50) DEFAULT NULL,
  `transaction_date` bigint(20) DEFAULT NULL,
  `status_code` int(11) NOT NULL,
  `status_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
