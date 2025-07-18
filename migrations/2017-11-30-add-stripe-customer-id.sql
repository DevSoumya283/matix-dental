ALTER TABLE `users` ADD `stripe_id` VARCHAR(40) NULL ;
UPDATE `users`, `user_payment_options`
SET    users.stripe_id = user_payment_options.token
WHERE  users.id = user_payment_options.user_id;