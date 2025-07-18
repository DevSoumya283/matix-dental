SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.088412046432495

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.001039981842041

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.013325929641724

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.010157823562622

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.038186073303223

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.032454013824463

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.0074338912963867

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00034809112548828

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00043296813964844

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00054502487182617

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00039792060852051

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0010180473327637

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00037384033203125

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` =0
AND `product_id` IS NULL 
 Execution Time:0.0001990795135498

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00048494338989258

INSERT INTO `promo_codes` (`title`, `code`, `discount`, `discount_type`, `discount_on`, `threshold_count`, `threshold_type`, `start_date`, `end_date`, `manufacturer_coupon`, `conditions`, `product_free`, `vendor_id`, `use_with_promos`, `active`, `created_at`, `updated_at`) VALUES ('Good lord walking upon', 'CDF456', '35', '20', '10', '70', '90', '2016-11-17', '2017-01-31', '1', 'Walking upon the street its good', '0', '1', '1', '1', '2016-11-17 10:55:13', '2016-11-17 10:55:13') 
 Execution Time:0.15763998031616

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034999847412109

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1' 
 Execution Time:0.00034189224243164

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039482116699219

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00043487548828125

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00036382675170898

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00032711029052734

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00028395652770996

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00030398368835449

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00030112266540527

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003669261932373

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00048685073852539

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037407875061035

UPDATE `promo_codes` SET `active` = '0', `updated_at` = '2016-11-17 10:55:27'
WHERE `id` IN('21', '22') 
 Execution Time:0.077181816101074

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036287307739258

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00030803680419922

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043296813964844

UPDATE `promo_codes` SET `active` = '1', `updated_at` = '2016-11-17 10:55:30'
WHERE `id` IN('21', '22') 
 Execution Time:0.037070035934448

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00049591064453125

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00046396255493164

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037384033203125

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00031280517578125

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041103363037109

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00038695335388184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040698051452637

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00028705596923828

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00049018859863281

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00036311149597168

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0004270076751709

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00043582916259766

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.025377988815308

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.038320064544678

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00039005279541016

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0003211498260498

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039291381835938

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00057291984558105

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00041079521179199

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00031208992004395

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00028014183044434

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00027990341186523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003969669342041

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00035595893859863

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002288818359375

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023698806762695

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00020194053649902

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023412704467773

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00042414665222168

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00040197372436523

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031709671020508

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00027799606323242

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00033307075500488

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00033402442932129

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00033998489379883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00027990341186523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043487548828125

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0003809928894043

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003211498260498

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00027990341186523

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00029706954956055

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00028491020202637

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00046682357788086

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.0003960132598877

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00032901763916016

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00035595893859863

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00034499168395996

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00030303001403809

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00033807754516602

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038504600524902

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00043106079101562

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039982795715332

UPDATE `promo_codes` SET `active` = '0', `updated_at` = '2016-11-17 11:13:29'
WHERE `id` IN('22') 
 Execution Time:0.064839839935303

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00033998489379883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00037503242492676

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040102005004883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00033402442932129

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023102760314941

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023102760314941

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039291381835938

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00043487548828125

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003669261932373

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00040817260742188

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00017619132995605

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00017094612121582

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00047111511230469

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00039386749267578

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00033402442932129

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00032591819763184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038599967956543

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00047087669372559

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026893615722656

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00025391578674316

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023102760314941

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00022292137145996

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00042295455932617

INSERT INTO `promo_codes` (`title`, `code`, `discount`, `discount_type`, `discount_on`, `threshold_count`, `threshold_type`, `start_date`, `end_date`, `manufacturer_coupon`, `conditions`, `product_free`, `vendor_id`, `use_with_promos`, `active`, `created_at`, `updated_at`) VALUES ('Sometest run', 'WER456', '45', '45', '34', '44', '3333', '2016-11-17', '2016-12-31', '1', 'Well said', '1', '1', '1', '1', '2016-11-17 11:15:12', '2016-11-17 11:15:12') 
 Execution Time:0.056465864181519

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00035595893859863

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1' 
 Execution Time:0.00050687789916992

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00035500526428223

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00026893615722656

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00024199485778809

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00018596649169922

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00021600723266602

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00025510787963867

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00030303001403809

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036716461181641

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00032591819763184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00046491622924805

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00047802925109863

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003199577331543

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00028896331787109

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00028395652770996

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00028109550476074

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037384033203125

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00062799453735352

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0005030632019043

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00026893615722656

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041389465332031

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00056004524230957

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003821849822998

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00033187866210938

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002288818359375

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023412704467773

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021696090698242

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00021815299987793

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040292739868164

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00049996376037598

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00027585029602051

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00026106834411621

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00033903121948242

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00028085708618164

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0001680850982666

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00016880035400391

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00018787384033203

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0001981258392334

SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0012328624725342

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00044107437133789

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00035190582275391

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00048208236694336

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00087690353393555

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00078511238098145

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00062203407287598

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00061488151550293

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00066089630126953

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00049614906311035

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00058197975158691

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00059103965759277

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00064396858215332

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00038790702819824

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00034117698669434

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00033783912658691

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00036382675170898

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00035190582275391

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0005638599395752

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00039386749267578

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00032782554626465

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0002739429473877

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00047898292541504

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00032281875610352

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00015997886657715

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00018095970153809

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00042200088500977

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00039291381835938

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00030803680419922

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00030303001403809

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031495094299316

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00031495094299316

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031208992004395

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00035214424133301

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00054717063903809

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00049304962158203

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041389465332031

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00040006637573242

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00029897689819336

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00029993057250977

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038599967956543

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00038409233093262

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002598762512207

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023579597473145

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021886825561523

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00026488304138184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038003921508789

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00027298927307129

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034618377685547

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00036501884460449

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023698806762695

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023603439331055

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00022006034851074

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00022196769714355

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00044393539428711

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00053310394287109

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00039386749267578

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00038909912109375

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036883354187012

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00027012825012207

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00017285346984863

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023007392883301

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00024986267089844

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00021815299987793

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002448558807373

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00027298927307129

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003819465637207

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00043892860412598

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00028705596923828

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0002598762512207

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00024604797363281

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00024795532226562

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026202201843262

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00025391578674316

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00049901008605957

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00060200691223145

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031304359436035

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00026106834411621

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00032496452331543

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00037598609924316

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034308433532715

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00026106834411621

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00017094612121582

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00017094612121582

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037884712219238

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00045490264892578

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00028300285339355

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00026512145996094

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00024509429931641

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00024604797363281

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023007392883301

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023794174194336

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037980079650879

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1' 
 Execution Time:0.00065493583679199

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0007631778717041

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1' 
 Execution Time:0.00060200691223145

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0004420280456543

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00039887428283691

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00036883354187012

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00030899047851562

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00034713745117188

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00034403800964355

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00043106079101562

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040102005004883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00041794776916504

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00045609474182129

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` = '<=now()' 
 Execution Time:0.00041699409484863

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00035500526428223

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00033402442932129

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023984909057617

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00025200843811035

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040483474731445

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` = '<=now()' 
 Execution Time:0.00040006637573242

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003960132598877

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00037503242492676

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00044608116149902

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= 'now()' 
 Execution Time:0.00032901763916016

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038599967956543

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00034999847412109

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023388862609863

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023913383483887

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039291381835938

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00035190582275391

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00017690658569336

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00017905235290527

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00015687942504883

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00015687942504883

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036787986755371

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00044703483581543

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00038003921508789

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00069212913513184

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00048208236694336

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00049686431884766

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003211498260498

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00029993057250977

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0004270076751709

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= 'now()' 
 Execution Time:0.00045895576477051

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00052404403686523

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0004889965057373

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037407875061035

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` = 'lte' 
 Execution Time:0.00045299530029297

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038409233093262

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00033807754516602

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00049304962158203

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00048303604125977

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003049373626709

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00027203559875488

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00025200843811035

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023198127746582

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036501884460449

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00044798851013184

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00030088424682617

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00028395652770996

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040888786315918

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00059604644775391

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040102005004883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= 'now()' 
 Execution Time:0.00071597099304199

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00054097175598145

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0006110668182373

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043511390686035

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= 'now()' 
 Execution Time:0.00053095817565918

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043916702270508

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00044512748718262

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00045490264892578

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= 'now();' 
 Execution Time:0.00043797492980957

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034403800964355

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00027203559875488

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00075984001159668

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= now() 
 Execution Time:0.00060796737670898

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003509521484375

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0003669261932373

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031590461730957

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00033998489379883

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003809928894043

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00046801567077637

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00073504447937012

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0005650520324707

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038504600524902

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00048112869262695

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00040888786315918

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00040102005004883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00052404403686523

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00087189674377441

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.0003659725189209

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037598609924316

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00034093856811523

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023794174194336

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00025701522827148

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023603439331055

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00024104118347168

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00024008750915527

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0002439022064209

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038504600524902

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `end_date` <= now() 
 Execution Time:0.00045585632324219

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00027894973754883

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00028705596923828

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00025010108947754

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00022792816162109

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00020289421081543

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00022411346435547

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00063204765319824

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00043797492980957

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00028204917907715

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00023913383483887

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00021791458129883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00024700164794922

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00028014183044434

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037002563476562

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00033211708068848

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041508674621582

UPDATE `promo_codes` SET `active` = '0', `updated_at` = '2016-11-17 02:08:26'
WHERE `id` IN('21') 
 Execution Time:0.10883188247681

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034904479980469

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0006110668182373

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041890144348145

UPDATE `promo_codes` SET `active` = '1', `updated_at` = '2016-11-17 02:08:34'
WHERE `id` IN('21', '22', '23') 
 Execution Time:0.06731390953064

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038003921508789

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00090789794921875

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038504600524902

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= now() 
 Execution Time:0.00033307075500488

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00025796890258789

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00027012825012207

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021719932556152

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00020194053649902

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00045418739318848

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00042605400085449

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043296813964844

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0004889965057373

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00047802925109863

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00049400329589844

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00049710273742676

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.0002288818359375

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00019288063049316

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.0001978874206543

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00017499923706055

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00033783912658691

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00077199935913086

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0004119873046875

SELECT *
FROM `shipping_options`
WHERE `vendor_id` = '1' 
 Execution Time:0.0006411075592041

SELECT *
FROM `roles` 
 Execution Time:0.0005190372467041

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00027608871459961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021219253540039

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00046014785766602

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018095970153809

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019001960754395

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00024294853210449

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019001960754395

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00021600723266602

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018095970153809

SELECT *
FROM `roles` 
 Execution Time:0.00039482116699219

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038313865661621

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00022602081298828

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00030303001403809

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00020313262939453

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00022506713867188

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016403198242188

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00020599365234375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014901161193848

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00025606155395508

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024986267089844

SELECT *
FROM `roles` 
 Execution Time:0.00043797492980957

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00031805038452148

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00025796890258789

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00078701972961426

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0003058910369873

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00030684471130371

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00021505355834961

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00026512145996094

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00021600723266602

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016903877258301

SELECT *
FROM `roles` 
 Execution Time:0.00054693222045898

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00065803527832031

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00032210350036621

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.0003359317779541

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019311904907227

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00022315979003906

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001680850982666

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00021791458129883

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016403198242188

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00020599365234375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00015997886657715

SELECT *
FROM `roles` 
 Execution Time:0.00043106079101562

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034999847412109

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021481513977051

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00031495094299316

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018787384033203

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00020599365234375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00025296211242676

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00037693977355957

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00029301643371582

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00046801567077637

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0005180835723877

SELECT *
FROM `roles` 
 Execution Time:0.00058412551879883

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00029397010803223

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00020313262939453

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00026488304138184

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017595291137695

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00020313262939453

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001368522644043

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00018596649169922

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00029516220092773

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00039196014404297

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00034713745117188

SELECT *
FROM `roles` 
 Execution Time:0.00042390823364258

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00026416778564453

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00029301643371582

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00029397010803223

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018405914306641

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.0003211498260498

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00025010108947754

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00023889541625977

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017189979553223

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00021696090698242

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014495849609375

SELECT *
FROM `roles` 
 Execution Time:0.0010700225830078

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00072503089904785

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00038814544677734

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00072312355041504

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00032997131347656

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00030088424682617

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018405914306641

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00025701522827148

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019598007202148

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00020813941955566

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017595291137695

