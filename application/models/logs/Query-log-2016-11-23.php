SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.016411066055298

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047206878662109

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.038838863372803

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.018769979476929

SELECT *
FROM `users`
WHERE `role_id` = '1' 
 Execution Time:0.00060701370239258

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0005800724029541

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00041484832763672

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00016188621520996

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00032806396484375

SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00058913230895996

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00028300285339355

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001530647277832

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00028514862060547

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.018875122070312

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.015921831130981

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00030708312988281

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00021982192993164

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00023007392883301

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00024199485778809

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.0002140998840332

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043106079101562

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00046992301940918

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.051001071929932

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.01649808883667

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00055599212646484

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00045895576477051

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00052285194396973

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00049400329589844

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034713745117188

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00036311149597168

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00042104721069336

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00021886825561523

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00016188621520996

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00017309188842773

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0001530647277832

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00016093254089355

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037884712219238

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NOT NULL 
 Execution Time:0.00031805038452148

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040006637573242

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `end_date` <= now() 
 Execution Time:0.00036406517028809

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002288818359375

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00025200843811035

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00022315979003906

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023221969604492

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021696090698242

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00025606155395508

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040411949157715

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00041699409484863

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00030708312988281

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00033688545227051

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00034999847412109

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00035905838012695

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00033307075500488

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00053715705871582

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00046586990356445

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040888786315918

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00038504600524902

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00032591819763184

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00030899047851562

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00025010108947754

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00029301643371582

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00030899047851562

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00023603439331055

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040698051452637

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '0'
AND `product_id` IS NULL 
 Execution Time:0.00030207633972168

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00042200088500977

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `end_date` <= now() 
 Execution Time:0.00038909912109375

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00029301643371582

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0003359317779541

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031518936157227

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.0003209114074707

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00045895576477051

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.0004270076751709

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.0003211498260498

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00028610229492188

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.0002899169921875

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00029611587524414

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00027084350585938

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043797492980957

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00061893463134766

SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00054097175598145

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00029492378234863

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00015592575073242

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00028514862060547

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00049805641174316

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00027108192443848

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00017690658569336

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00031018257141113

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00066804885864258

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00039815902709961

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00017809867858887

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.0003211498260498

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00057601928710938

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00038409233093262

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00030708312988281

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00037908554077148

SELECT *
FROM `users`
WHERE `role_id` = '1' 
 Execution Time:0.00052404403686523

SELECT *
FROM `users`
WHERE `role_id` = '1' 
 Execution Time:0.00050592422485352

SELECT *
FROM `users`
WHERE `role_id` = '1' 
 Execution Time:0.00061416625976562

SELECT *
FROM `users`
WHERE `role_id` = '1' 
 Execution Time:0.00058102607727051

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00049185752868652

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00028014183044434

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00018405914306641

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00031495094299316

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044703483581543

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00041294097900391

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047707557678223

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043582916259766

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00054001808166504

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00042915344238281

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045299530029297

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00053906440734863

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00072193145751953

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00055813789367676

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00042915344238281

SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00053596496582031

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.0004889965057373

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022196769714355

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00033688545227051

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040197372436523

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.080353975296021

UPDATE `vendors` SET `name` = 'Harry Page', `updated_at` = '2016-11-23 08:02:34'
WHERE `id` = '1' 
 Execution Time:0.11590003967285

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039887428283691

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00030088424682617

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0007178783416748

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00048613548278809

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00033402442932129

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.0004880428314209

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045585632324219

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00048995018005371

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045490264892578

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0004580020904541

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00050497055053711

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045108795166016

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00050020217895508

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044512748718262

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049901008605957

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00055694580078125

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051403045654297

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00054287910461426

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047111511230469

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046205520629883

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046801567077637

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00055503845214844

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044107437133789

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00070881843566895

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051593780517578

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044608116149902

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049304962158203

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00052499771118164

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044393539428711

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046110153198242

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00056004524230957

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00053286552429199

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00052809715270996

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00056791305541992

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049710273742676

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051307678222656

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00055289268493652

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00054001808166504

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.054309129714966

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0005650520324707

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.073952913284302

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043606758117676

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.042301893234253

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00048208236694336

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.061310052871704

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046181678771973

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.051496982574463

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0006260871887207

UPDATE `users` SET `first_name` = 'Donald Xavier', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.052738904953003

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00062394142150879

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00050806999206543

UPDATE `users` SET `first_name` = 'Donald XavierFlat', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.035846948623657

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0063049793243408

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045204162597656

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00053787231445312

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.039643049240112

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00036096572875977

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043201446533203

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043892860412598

UPDATE `users` SET `first_name` = 'Donald Graph', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.039749145507812

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0005030632019043

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.052050828933716

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049400329589844

SELECT *
FROM `users`
WHERE `email` = 'renuga@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00066900253295898

SELECT *
FROM `users`
WHERE `email` = 'renuga@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.000518798828125

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00047111511230469

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00034999847412109

SELECT *
FROM `privileges`
WHERE `role_id` = '12' 
 Execution Time:0.00063300132751465

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00058603286743164

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00049304962158203

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00044989585876465

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00053215026855469

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0003349781036377

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00018787384033203

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.0003199577331543

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00042104721069336

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043702125549316

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00061893463134766

SELECT *
FROM `users`
WHERE `email` = 'renuga@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00054717063903809

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00031709671020508

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00019407272338867

SELECT *
FROM `privileges`
WHERE `role_id` = '12' 
 Execution Time:0.00035190582275391

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00044989585876465

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00047802925109863

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00052404403686523

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047612190246582

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00022387504577637

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00035309791564941

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00042605400085449

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.057363986968994

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00059103965759277

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051307678222656

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046396255493164

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046896934509277

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046801567077637

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043892860412598

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045394897460938

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046610832214355

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051093101501465

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00055909156799316

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00061821937561035

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051188468933105

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.046650171279907

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047087669372559

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.041450023651123

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043296813964844

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.046391010284424

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00063204765319824

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.030977964401245

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00087404251098633

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047516822814941

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044417381286621

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043010711669922

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0004880428314209

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047397613525391

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00052499771118164

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00051093101501465

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045990943908691

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00048208236694336

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0006401538848877

UPDATE `users` SET `first_name` = 'Donald Duck', `email` = 'dentomatix@webcrafters.in'
WHERE `id` = '127' 
 Execution Time:0.058228015899658

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00050997734069824

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00048494338989258

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00065803527832031

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045514106750488

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00070405006408691

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043702125549316

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046515464782715

UPDATE `users` SET `reset_password_token` = '', `password` = NULL, `password_last_updated_at` = '2016-11-23 12:08:07', `updated_at` = '2016-11-23 12:08:07'
WHERE `id` IS NULL 
 Execution Time:0.00027203559875488

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00052499771118164

UPDATE `users` SET `reset_password_token` = '', `password` = NULL, `password_last_updated_at` = '2016-11-23 12:08:58', `updated_at` = '2016-11-23 12:08:58'
WHERE `id` = '127' 
 Execution Time:0.058846950531006

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00056600570678711

UPDATE `users` SET `reset_password_token` = '', `password` = '1f3870be274f6c49b3e31a0c6728957f', `password_last_updated_at` = '2016-11-23 12:12:00', `updated_at` = '2016-11-23 12:12:00'
WHERE `id` = '127' 
 Execution Time:0.039183855056763

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0005340576171875

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = '1f3870be274f6c49b3e31a0c6728957f' 
 Execution Time:0.00053310394287109

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00039100646972656

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00036501884460449

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00050687789916992

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00042200088500977

UPDATE `users` SET `reset_password_token` = '', `password` = 'e19d5cd5af0378da05f63f891c7467af', `password_last_updated_at` = '2016-11-23 12:12:44', `updated_at` = '2016-11-23 12:12:44'
WHERE `id` = '127' 
 Execution Time:0.023494958877563

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0004570484161377

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049114227294922

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043988227844238

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047802925109863

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044107437133789

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00047397613525391

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046992301940918

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043201446533203

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0004417896270752

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00042104721069336

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0004880428314209

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044107437133789

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00060820579528809

UPDATE `users` SET `reset_password_token` = '', `password` = '1f3870be274f6c49b3e31a0c6728957f', `password_last_updated_at` = '2016-11-23 12:25:58', `updated_at` = '2016-11-23 12:25:58'
WHERE `id` = '127' 
 Execution Time:0.052827835083008

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046610832214355

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00053000450134277

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00050497055053711

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00053000450134277

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = '1f3870be274f6c49b3e31a0c6728957f' 
 Execution Time:0.00049805641174316

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00025391578674316

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00015711784362793

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00028800964355469

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043487548828125

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045108795166016

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045299530029297

UPDATE `users` SET `reset_password_token` = '', `password` = 'e19d5cd5af0378da05f63f891c7467af', `password_last_updated_at` = '2016-11-23 12:28:55', `updated_at` = '2016-11-23 12:28:55'
WHERE `id` = '127' 
 Execution Time:0.046905994415283

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00044488906860352

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00048995018005371

UPDATE `users` SET `reset_password_token` = '', `password` = 'acaa16770db76c1ffb9cee51c3cabfcf', `password_last_updated_at` = '2016-11-23 12:29:27', `updated_at` = '2016-11-23 12:29:27'
WHERE `id` = '127' 
 Execution Time:0.13557195663452

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0004889965057373

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00049805641174316

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'acaa16770db76c1ffb9cee51c3cabfcf' 
 Execution Time:0.00066184997558594

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00038599967956543

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00021910667419434

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00033807754516602

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049209594726562

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00046706199645996

UPDATE `users` SET `reset_password_token` = '', `password` = 'e19d5cd5af0378da05f63f891c7467af', `password_last_updated_at` = '2016-11-23 12:29:58', `updated_at` = '2016-11-23 12:29:58'
WHERE `id` = '127' 
 Execution Time:0.089772939682007

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00043511390686035

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00045585632324219

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049400329589844

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00052714347839355

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00038290023803711

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00019693374633789

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00032782554626465

SELECT *
FROM `organization_groups` 
 Execution Time:0.026620149612427

SELECT *
FROM `organizations`
WHERE `id` = '1' 
 Execution Time:0.012614011764526

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00031495094299316

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00020098686218262

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00034594535827637

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00044822692871094

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00032210350036621

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00027084350585938

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00022387504577637

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00024008750915527

SELECT *
FROM `organizations`
WHERE `id` = '4' 
 Execution Time:0.0003049373626709

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00030207633972168

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00040411949157715

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00046086311340332

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.0002129077911377

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00013208389282227

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00025582313537598

SELECT *
FROM `vendors` 
 Execution Time:0.00074386596679688

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00057196617126465

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.078106880187988

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00035786628723145

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00037097930908203

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00028204917907715

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00031900405883789

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00026607513427734

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00046706199645996

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00041604042053223

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00029516220092773

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00027298927307129

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00030684471130371

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00018405914306641

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.0002739429473877

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00018095970153809

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00028491020202637

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00022196769714355

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00029516220092773

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00056290626525879

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00037813186645508

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00018978118896484

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00033903121948242

SELECT *
FROM `roles` 
 Execution Time:0.00039887428283691

SELECT *
FROM `users`
WHERE `id` = '228' 
 Execution Time:0.00045108795166016

SELECT *
FROM `users`
WHERE `id` = '228' 
 Execution Time:0.0004889965057373

UPDATE `users` SET `first_name` = 'raam', `last_name` = 'raam', `password` = 'e19d5cd5af0378da05f63f891c7467af', `updated_at` = '2016-11-23 12:43:37'
WHERE `id` = '228' 
 Execution Time:0.035890102386475

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00046205520629883

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00021100044250488

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00011897087097168

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.0002589225769043

SELECT *
FROM `vendors` 
 Execution Time:0.00051522254943848

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00042295455932617

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00056600570678711

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00037503242492676

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00042200088500977

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.000244140625

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00031304359436035

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00032401084899902

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00046110153198242

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00034999847412109

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00045990943908691

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00037789344787598

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00053286552429199

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00036501884460449

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00061202049255371

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00034999847412109

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00043892860412598

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.0002598762512207

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00032210350036621

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00025105476379395

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.0004429817199707

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00057291984558105

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00027704238891602

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00017786026000977

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00031399726867676

SELECT *
FROM `organization_groups` 
 Execution Time:0.00049996376037598

SELECT *
FROM `organizations`
WHERE `id` = '1' 
 Execution Time:0.00070405006408691

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00085783004760742

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00040602684020996

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.0004279613494873

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00047111511230469

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.0003960132598877

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00045299530029297

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00061392784118652

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00059700012207031

SELECT *
FROM `organizations`
WHERE `id` = '4' 
 Execution Time:0.00045013427734375

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00058603286743164

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00060486793518066

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00057291984558105

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00033211708068848

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.0002601146697998

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00037002563476562

SELECT *
FROM `organization_groups` 
 Execution Time:0.00038504600524902

SELECT *
FROM `organizations`
WHERE `id` = '1' 
 Execution Time:0.00031805038452148

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00025200843811035

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00017285346984863

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00019288063049316

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00021505355834961

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.0001678466796875

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00019001960754395

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.0002140998840332

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00016903877258301

SELECT *
FROM `organizations`
WHERE `id` = '4' 
 Execution Time:0.00020098686218262

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00019097328186035

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00014114379882812

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00057196617126465

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00036001205444336

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00015997886657715

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00030398368835449

SELECT *
FROM `vendors` 
 Execution Time:0.00050020217895508

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00030112266540527

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00026702880859375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00018596649169922

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00020980834960938

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00017285346984863

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00021100044250488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00017309188842773

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00018787384033203

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00015616416931152

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00017809867858887

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00014185905456543

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00017499923706055

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00014019012451172

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00017309188842773

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00018000602722168

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00021910667419434

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00015997886657715

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00019502639770508

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00014710426330566

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00018095970153809

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00050497055053711

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00025010108947754

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00015711784362793

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00028705596923828

SELECT *
FROM `organization_groups` 
 Execution Time:0.00040483474731445

SELECT *
FROM `organizations`
WHERE `id` = '1' 
 Execution Time:0.00032496452331543

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00033807754516602

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00025677680969238

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00024795532226562

SELECT *
FROM `users`
WHERE `id` = '213' 
 Execution Time:0.00027298927307129

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00018787384033203

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.0002591609954834

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00031685829162598

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00029206275939941

SELECT *
FROM `organizations`
WHERE `id` = '4' 
 Execution Time:0.00028204917907715

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00024509429931641

SELECT *
FROM `roles`
WHERE `id` = '12' 
 Execution Time:0.00020480155944824

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00052785873413086

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00030088424682617

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00013089179992676

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.00026082992553711

SELECT *
FROM `roles` 
 Execution Time:0.00054287910461426

SELECT *
FROM `roles` 
 Execution Time:0.00038599967956543

SELECT *
FROM `roles` 
 Execution Time:0.00042390823364258

SELECT *
FROM `users`
WHERE `email` = 'dentomatix@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0012469291687012

SELECT *
FROM `users`
WHERE `id` = '127' 
 Execution Time:0.00049805641174316

SELECT *
FROM `roles`
WHERE `id` = '1' 
 Execution Time:0.00029087066650391

SELECT *
FROM `privileges`
WHERE `role_id` = '1' 
 Execution Time:0.0004570484161377

SELECT *
FROM `vendors` 
 Execution Time:0.00066995620727539

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00047612190246582

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00075697898864746

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00055789947509766

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00034213066101074

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.0001990795135498

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00027298927307129

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00021100044250488

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.0002748966217041

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00020098686218262

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00027704238891602

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00018501281738281

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.0002439022064209

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001828670501709

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00026893615722656

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00017905235290527

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00023484230041504

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00018000602722168

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00027012825012207

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00016903877258301

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.0002598762512207

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00048303604125977

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00044918060302734

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00043702125549316

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00040507316589355

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00033402442932129

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00026988983154297

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00023698806762695

SELECT *
FROM `vendors` 
 Execution Time:0.00056600570678711

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00033378601074219

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00032615661621094

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00026893615722656

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00029802322387695

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00026106834411621

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00029993057250977

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00026202201843262

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00029778480529785

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00026297569274902

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00031900405883789

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00023102760314941

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00030112266540527

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0002129077911377

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00024294853210449

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00020384788513184

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.0002131462097168

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00016999244689941

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00021815299987793

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.0001678466796875

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00023102760314941

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00045394897460938

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00041699409484863

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00022292137145996

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00017809867858887

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00023412704467773

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00049304962158203

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00041508674621582

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00023198127746582

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00020694732666016

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00038981437683105

SELECT *
FROM `vendors` 
 Execution Time:0.00067901611328125

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003209114074707

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00032401084899902

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00026988983154297

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00024795532226562

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00021004676818848

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00023579597473145

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00019598007202148

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00022792816162109

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00019502639770508

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00022697448730469

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00017499923706055

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00019693374633789

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00016403198242188

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00020003318786621

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00016403198242188

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00019502639770508

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00019288063049316

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00021696090698242

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00023007392883301

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00041103363037109

SELECT *
FROM `vendors`
WHERE `id` = '21' 
 Execution Time:0.00081801414489746

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00044012069702148

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '21' 
 Execution Time:0.0002739429473877

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00019717216491699

SELECT *
FROM `users`
WHERE `id` = '226' 
 Execution Time:0.00021195411682129

SELECT *
FROM `vendors` 
 Execution Time:0.00049304962158203

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026917457580566

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00026607513427734

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00018191337585449

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.0001988410949707

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.0001828670501709

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00021505355834961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00017690658569336

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00021004676818848

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00017714500427246

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.0002140998840332

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00014281272888184

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00017499923706055

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00018000602722168

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00020790100097656

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.0001518726348877

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00017786026000977

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00014495849609375

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00018692016601562

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00014615058898926

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.0001838207244873

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00043606758117676

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00042104721069336

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00041508674621582

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00040292739868164

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00044512748718262

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00041484832763672

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00049805641174316

SELECT *
FROM `vendors` 
 Execution Time:0.0005040168762207

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00039100646972656

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00034213066101074

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00022983551025391

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00028109550476074

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00020408630371094

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00022983551025391

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.0001838207244873

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00023818016052246

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00015902519226074

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00023508071899414

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00016689300537109

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00023102760314941

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00017595291137695

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.0002288818359375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.0001680850982666

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00023508071899414

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00017309188842773

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00024986267089844

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00017499923706055

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00024008750915527

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00046706199645996

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00039911270141602

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00023007392883301

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00018811225891113

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00023698806762695

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.0004889965057373

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00050497055053711

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.0003809928894043

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00031018257141113

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00030803680419922

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00060796737670898

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00043702125549316

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00028705596923828

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00022602081298828

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.0003199577331543

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00043106079101562

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00023794174194336

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00014901161193848

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00012612342834473

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00017905235290527

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00046300888061523

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00037407875061035

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00022006034851074

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00018000602722168

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00021481513977051

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00045418739318848

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00032997131347656

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00021100044250488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00016903877258301

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00023412704467773

SELECT *
FROM `vendors` 
 Execution Time:0.00053811073303223

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003669261932373

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00033402442932129

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00022315979003906

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00024700164794922

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00017690658569336

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00030994415283203

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00030803680419922

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00049090385437012

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00041985511779785

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00053000450134277

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00039196014404297

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.0004880428314209

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00047683715820312

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00069499015808105

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00052714347839355

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00069785118103027

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00049304962158203

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00068092346191406

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00051403045654297

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00059008598327637

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.0004580020904541

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00032401084899902

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00022292137145996

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00019097328186035

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00023412704467773

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00021600723266602

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00020885467529297

SELECT *
FROM `vendors` 
 Execution Time:0.00079989433288574

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00034880638122559

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00034904479980469

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00022697448730469

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00024604797363281

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00017499923706055

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00024700164794922

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00017690658569336

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.0002291202545166

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00018692016601562

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00031018257141113

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00058388710021973

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00050616264343262

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0003809928894043

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.0004878044128418

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.0002589225769043

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00030708312988281

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00019407272338867

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00028395652770996

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00018405914306641

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00026202201843262

SELECT *
FROM `vendors` 
 Execution Time:0.00052094459533691

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002598762512207

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00026082992553711

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00018095970153809

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00021195411682129

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00017189979553223

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00020599365234375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00017690658569336

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00020003318786621

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00016903877258301

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00023388862609863

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00019407272338867

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00020313262939453

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00016593933105469

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00023913383483887

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00017213821411133

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00030803680419922

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00018310546875

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00027704238891602

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00019097328186035

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00025486946105957

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00042200088500977

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00025582313537598

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00016283988952637

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00012779235839844

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00015902519226074

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00015687942504883

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00014901161193848

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '1' 
 Execution Time:0.00015401840209961

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00044107437133789

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00044703483581543

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00027203559875488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00020217895507812

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00024104118347168

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00022506713867188

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00027298927307129

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '1' 
 Execution Time:0.00029397010803223

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00043201446533203

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.0004420280456543

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026702880859375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021195411682129

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00022697448730469

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00025010108947754

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00022602081298828

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '1' 
 Execution Time:0.00029301643371582

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00062108039855957

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00043392181396484

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00029993057250977

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00030708312988281

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00032806396484375

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.0002591609954834

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00031304359436035

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '1' 
 Execution Time:0.00028896331787109

SELECT *
FROM `vendors` 
 Execution Time:0.00057005882263184

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00027084350585938

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00026893615722656

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00018596649169922

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00021100044250488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00017619132995605

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00021791458129883

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00017690658569336

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00021100044250488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00014901161193848

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00021100044250488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00017499923706055

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00021100044250488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00014996528625488

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00017905235290527

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00012898445129395

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00017309188842773

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00014090538024902

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00019598007202148

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00014400482177734

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00017499923706055

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00043702125549316

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00039505958557129

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00024294853210449

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00026392936706543

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00025701522827148

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '14' 
 Execution Time:0.00034284591674805

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00047087669372559

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00044107437133789

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00035190582275391

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00031900405883789

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00043296813964844

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '14' 
 Execution Time:0.00048017501831055

SELECT *
FROM `vendors`
WHERE `id` = '14' 
 Execution Time:0.00043582916259766

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00029492378234863

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '14' 
 Execution Time:0.00015497207641602

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.0001220703125

SELECT *
FROM `users`
WHERE `id` = '192' 
 Execution Time:0.00019001960754395

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '14' 
 Execution Time:0.00017595291137695

SELECT *
FROM `vendors` 
 Execution Time:0.00059199333190918

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00035190582275391

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00036406517028809

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00023698806762695

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.00029492378234863

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.0002288818359375

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00026988983154297

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.0002601146697998

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00024104118347168

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00020003318786621

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.00023603439331055

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00019407272338867

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00020289421081543

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00014400482177734

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00022101402282715

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.00021481513977051

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.00024700164794922

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00026297569274902

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00032305717468262

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00024199485778809

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.00025510787963867

SELECT *
FROM `vendors`
WHERE `id` = '1' 
 Execution Time:0.00047397613525391

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00031685829162598

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021505355834961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00019598007202148

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00023412704467773

SELECT *
FROM `users`
WHERE `id` = '225' 
 Execution Time:0.00022292137145996

SELECT *
FROM `users`
WHERE `id` = '227' 
 Execution Time:0.00021600723266602

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN '2016-11-01' and now()
AND `vendor_id` = '1' 
 Execution Time:0.00024104118347168

SELECT *
FROM `vendors` 
 Execution Time:0.0005040168762207

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002140998840332

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '1' 
 Execution Time:0.00020599365234375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '14' 
 Execution Time:0.00012397766113281

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '14' 
 Execution Time:0.0001521110534668

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '15' 
 Execution Time:0.00011396408081055

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '15' 
 Execution Time:0.00014615058898926

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '16' 
 Execution Time:0.00011396408081055

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '16' 
 Execution Time:0.00013995170593262

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '17' 
 Execution Time:0.00011086463928223

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '17' 
 Execution Time:0.0001521110534668

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '18' 
 Execution Time:0.00010895729064941

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '18' 
 Execution Time:0.00013899803161621

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001068115234375

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '19' 
 Execution Time:0.00014281272888184

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '20' 
 Execution Time:0.0001070499420166

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '20' 
 Execution Time:0.0001370906829834

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '21' 
 Execution Time:0.00010585784912109

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '21' 
 Execution Time:0.00014495849609375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '22' 
 Execution Time:0.00010800361633301

SELECT *
FROM `orders`
WHERE `created_at` BETWEEN YEAR("2016-01-01") and now()
AND `vendor_id` = '22' 
 Execution Time:0.0001380443572998

