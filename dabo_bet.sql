 `branches`
(
    `id`         bigint UNSIGNED                                NOT NULL AUTO_INCREMENT,
    `name`
    `type`       enum ('main','sub')
    `created_at` timestamp                                      NULL DEFAULT NULL,
    `updated_at` timestamp                                      NULL DEFAULT NULL,
)

 `brands`
(
    `id`              bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `name`
    `product_type_id` bigint UNSIGNED
    `created_at`      timestamp                               NULL DEFAULT NULL,
    `updated_at`      timestamp                               NULL DEFAULT NULL,
)

 `cache`
(
    `key`
    `value`      mediumtext
    `expiration` int
)

 `cache_locks`
(
    `key`
    `owner`
    `expiration` int
)

 `cash_collecteds`
(
    `id`
    `branch_id`  bigint UNSIGNED
    `amount`     double
    `created_at` timestamp       NULL DEFAULT NULL,
    `updated_at` timestamp       NULL DEFAULT NULL,
)

 `commissions`
(
    `id`                      bigint UNSIGNED                                       NOT NULL AUTO_INCREMENT,
    `product_id`              bigint UNSIGNED
    `discount_amount`         double
    `commission_recipient_id` bigint UNSIGNED
    `status`                  enum ('active','inactive')
    `created_at`              timestamp                                             NULL DEFAULT NULL,
    `updated_at`              timestamp                                             NULL DEFAULT NULL,
)

 `commission_recipients`
(
    `id`         bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `name`
    `branch_id`  bigint UNSIGNED
    `created_at` timestamp                               NULL DEFAULT NULL,
    `updated_at` timestamp                               NULL DEFAULT NULL,
)
 `daily_commissions`
(
        `id`
        `quantity` double
        `commission_recipient_id` bigint UNSIGNED
        `created_at`
        `updated_at`
        `daily_sales_id` bigint UNSIGNED
)
 `daily_inventory_outs` (
      `id`
      `quantity` double
      `inventory_item_id` bigint UNSIGNED
      `user_id` bigint UNSIGNED
      `receiver_user_id` bigint UNSIGNED DEFAULT NULL,
      `created_at` ,
      `updated_at` ,
      `unit_id` bigint UNSIGNED,
      daily_inventory_outs_receiver_user_id_foreig
)

 `daily_production_adjustments`
 ( `id`
     `product_id` bigint UNSIGNED
     `quantity` double
     `unit_id` bigint UNSIGNED
     `type` enum ('damaged','stale','worker_error')
     `remark` DEFAULT NULL,
     `created_at` ,
     `updated_at` ,)

 `daily_sales`
 ( `id`
     `product_id` bigint UNSIGNED
     `quantity` double
     `adari` double
     `branch_id` bigint UNSIGNED
     `created_at` ,
     `updated_at` ,)

 `expenses`
 ( `id`
     `description`
     `amount` double
     `user_id` bigint UNSIGNED
     `branch_id` bigint UNSIGNED
     `type` enum ('expected','unexpected')
     `created_at` ,
     `updated_at` ,
 )

 `failed_jobs`
 ( `id`
     `uuid`
     `connection` text
     `queue` text
     `payload` longtext
     `exception` longtext
     `failed_at` timestamp CURRENT_TIMESTAMP
)
 `inventory_adjustments` (
  `id`
  `inventory_item_id` bigint UNSIGNED
  `operation` enum('addition','subtraction')    'subtraction',
  `quantity` double
  `unit_id` bigint UNSIGNED
  `remark` DEFAULT NULL,
  `created_at` ,
  `updated_at`
  )
 `inventory_items` (
  `id`
  `item_name`
  `brand_id` bigint UNSIGNED
  `unit_id` bigint UNSIGNED
  `quantity` double
  `price` double
  `total_price` double
  `user_id` bigint UNSIGNED
  `created_at` ,
  `updated_at` ,
  `batch_number` int
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 =;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
 `jobs` (
  `id`
  `queue`
  `payload` longtext
  `attempts` tinyint UNSIGNED
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED
  `created_at` int UNSIGNED
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 =;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
 `job_batches` (
  `id`
  `name`
  `total_jobs` int
  `pending_jobs` int
  `failed_jobs` int
  `failed_job_ids` longtext
  `options` medium
  `cancelled_at` int DEFAULT NULL,
  `created_at` int
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 =;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
 `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration`
  `batch` int
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 =;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
 `password_reset_tokens` (
  `email`
  `token`
  `created_at` ,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 =;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
 `personal_access_tokens`
(
    `id`             bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `tokenable_type`
    `tokenable_id`   bigint UNSIGNED
    `name`
    `token`          varchar(64)
    `abilities`
    `last_used_at`   timestamp                               NULL DEFAULT NULL,
    `expires_at`     timestamp                               NULL DEFAULT NULL,
    `created_at`     timestamp                               NULL DEFAULT NULL,
    `updated_at`     timestamp                               NULL DEFAULT NULL,

)
 `products`
(
    `id`         bigint UNSIGNED                                           NOT NULL AUTO_INCREMENT,
    `name`
    `type`       enum ('Bread','Cake','Others')
    `unit_price` double
    `created_at` timestamp                                                 NULL DEFAULT NULL,
    `updated_at` timestamp                                                 NULL DEFAULT NULL,

)
 `product_types`
(
    `id`         bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `name`
    `created_at` timestamp                               NULL DEFAULT NULL,
    `updated_at` timestamp                               NULL DEFAULT NULL,
)

 `recipes`
(
    `id`          bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `name`
    `product_id`  bigint UNSIGNED
    `created_at`  timestamp                               NULL DEFAULT NULL,
    `updated_at`  timestamp                               NULL DEFAULT NULL,
    `instruction`
)

 `recipe_inventory_items`
(
    `id`
    `recipe_id`         bigint UNSIGNED
    `inventory_item_id` bigint UNSIGNED
    `quantity`          double
    `unit_id`           bigint UNSIGNED
    `created_at`        timestamp       NULL DEFAULT NULL,
    `updated_at`        timestamp       NULL DEFAULT NULL,

)
 `sessions`
(
    `id`
    `user_id`       bigint UNSIGNED                        DEFAULT NULL,
    `ip_address`    varchar(45)   DEFAULT NULL,
    `user_agent`
    `payload`       longtext
    `last_activity` int
)

 `standard_batch_varieties`
(
    `id`                            bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `name`                           'default',
    `recipe_id`                     bigint UNSIGNED
    `single_factor_expected_output` double
    `created_at`                    timestamp                               NULL     DEFAULT NULL,
    `updated_at`                    timestamp                               NULL     DEFAULT NULL,
)

 `units`
(
    `id`         bigint UNSIGNED                         NOT NULL AUTO_INCREMENT,
    `name`
    `created_at` timestamp                               NULL DEFAULT NULL,
    `updated_at` timestamp                               NULL DEFAULT NULL,
)

 `users`
(
    `id`                        bigint UNSIGNED                                                          NOT NULL AUTO_INCREMENT,
    `name`
    `email`
    `email_verified_at`         timestamp                                                                NULL     DEFAULT NULL,
    `password`
    `two_factor_secret`
    `two_factor_recovery_codes`
    `two_factor_confirmed_at`   timestamp                                                                NULL     DEFAULT NULL,
    `remember_token`
    `profile_photo_path`                                                DEFAULT NULL,
    `created_at`                timestamp                                                                NULL     DEFAULT NULL,
    `updated_at`                timestamp                                                                NULL     DEFAULT NULL,
    `role`                      enum ('admin','store_keeper','worker','user')    'user',
)

 `work_orders` (
  `id`
  `standard_batch_variety_id` bigint UNSIGNED
  `variety_factor` double  '1',
  `output_count` double
  `created_at` ,
  `updated_at` ,

ALTER TABLE `daily_inventory_outs`
  ADD CONSTRAINT `n` FOREIGN KEY (`receiver_user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
