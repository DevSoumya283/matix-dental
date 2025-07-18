SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.086386919021606

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00047421455383301

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.030098915100098

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.010782957077026

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.028278112411499

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.0243079662323

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.020395994186401

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00040793418884277

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00038814544677734

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00038599967956543

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00033688545227051

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00046086311340332

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00044107437133789

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00032210350036621

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
 Execution Time:0.00027894973754883

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.00030708312988281

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00042295455932617

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041007995605469

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00034284591674805

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037908554077148

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `active` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00043416023254395

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.027874946594238

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.017842054367065

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026702880859375

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00021004676818848

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00020003318786621

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00030016899108887

SELECT *
FROM `roles` 
 Execution Time:0.00047707557678223

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00048208236694336

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00048708915710449

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00032401084899902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023007392883301

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00030684471130371

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00025010108947754

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.0002751350402832

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022387504577637

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.0003049373626709

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022411346435547

DELETE FROM `users`
WHERE `id` IN('') 
 Execution Time:0.00040507316589355

DELETE FROM `users`
WHERE `id` IN('') 
 Execution Time:0.0003199577331543

SELECT *
FROM `roles` 
 Execution Time:0.00046896934509277

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00051999092102051

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00032210350036621

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00043797492980957

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00031304359436035

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00037717819213867

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002591609954834

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00030803680419922

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018191337585449

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00029802322387695

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017976760864258

DELETE FROM `users`
WHERE `id` IN('13') 
 Execution Time:0.00031781196594238

DELETE FROM `users`
WHERE `id` IN('13') 
 Execution Time:0.00030899047851562

DELETE FROM `users`
WHERE `id` IN('13') 
 Execution Time:0.00032901763916016

DELETE FROM `users`
WHERE `id` IN('13', '11') 
 Execution Time:0.00032305717468262

DELETE FROM `users`
WHERE `id` IN('13', '11') 
 Execution Time:0.0004727840423584

DELETE FROM `users`
WHERE `id` IN('13', '11') 
 Execution Time:0.0004119873046875

SELECT *
FROM `roles` 
 Execution Time:0.00058698654174805

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00035905838012695

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00017595291137695

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00028395652770996

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016212463378906

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00020003318786621

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00013279914855957

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00020003318786621

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001380443572998

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00021696090698242

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016903877258301

DELETE FROM `users`
WHERE `id` IN('188', '182') 
 Execution Time:0.055025815963745

SELECT *
FROM `roles` 
 Execution Time:0.00059819221496582

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00034213066101074

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00027298927307129

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00032806396484375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002288818359375

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00030517578125

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00021505355834961

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00023198127746582

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00028800964355469

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00024890899658203

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00041794776916504

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL 
 Execution Time:0.00052499771118164

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` = 1 
 Execution Time:0.00056886672973633

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NOT NULL
AND `active` =0 
 Execution Time:0.00073409080505371

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL 
 Execution Time:0.00033307075500488

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` = 1 
 Execution Time:0.0003819465637207

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1'
AND `product_id` IS NULL
AND `active` =0 
 Execution Time:0.00038719177246094

SELECT *
FROM `roles` 
 Execution Time:0.00035905838012695

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00019001960754395

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.0001368522644043

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00020194053649902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011301040649414

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00014901161193848

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:9.8943710327148E-5

SELECT *
FROM `users`
WHERE `id` = '182' 
 Execution Time:0.00013613700866699

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00014805793762207

SELECT *
FROM `users`
WHERE `id` = '188' 
 Execution Time:0.00017499923706055

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00025391578674316

SELECT *
FROM `roles` 
 Execution Time:0.00038695335388184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00023913383483887

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00019502639770508

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017404556274414

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00021696090698242

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016498565673828

SELECT *
FROM `roles` 
 Execution Time:0.00042200088500977

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00031518936157227

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '1' 
 Execution Time:0.00021719932556152

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00026512145996094

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018000602722168

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00020098686218262

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014805793762207

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00067400932312012

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026106834411621

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011992454528809

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00037002563476562

SELECT *
FROM `roles` 
 Execution Time:0.00043797492980957

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00037384033203125

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00021505355834961

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0002751350402832

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '217' 
 Execution Time:0.00042009353637695

UPDATE `users` SET `first_name` = 'Flyover', `last_name` = 'F', `password` = 'e19d5cd5af0378da05f63f891c7467af', `updated_at` = '2016-11-19 07:39:53'
WHERE `id` = '217' 
 Execution Time:0.029262065887451

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00052404403686523

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00038790702819824

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00031185150146484

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00047087669372559

SELECT *
FROM `roles` 
 Execution Time:0.00046300888061523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00032997131347656

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00013399124145508

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014305114746094

SELECT *
FROM `users`
WHERE `id` = '217' 
 Execution Time:0.00017404556274414

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011086463928223

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00072598457336426

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0004889965057373

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00030899047851562

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00043797492980957

SELECT *
FROM `roles` 
 Execution Time:0.00041913986206055

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00038695335388184

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001978874206543

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00024199485778809

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001671314239502

SELECT *
FROM `users`
WHERE `id` = '217' 
 Execution Time:0.0008080005645752

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00022792816162109

SELECT *
FROM `roles` 
 Execution Time:0.00040888786315918

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00037789344787598

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00021004676818848

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018215179443359

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00044608116149902

UPDATE `users` SET `first_name` = 'Rocker', `last_name` = 'Instreet', `password` = 'e19d5cd5af0378da05f63f891c7467af', `updated_at` = '2016-11-19 11:21:27'
WHERE `id` = '218' 
 Execution Time:0.04033899307251

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00056886672973633

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00083804130554199

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00028705596923828

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00042605400085449

SELECT *
FROM `roles` 
 Execution Time:0.00041413307189941

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00027990341186523

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00032401084899902

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00024700164794922

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018215179443359

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023317337036133

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00049185752868652

UPDATE `users` SET `first_name` = 'RockerIn', `last_name` = 'street', `password` = 'e19d5cd5af0378da05f63f891c7467af', `updated_at` = '2016-11-19 11:22:21'
WHERE `id` = '219' 
 Execution Time:0.024276971817017

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0011589527130127

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00042295455932617

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022101402282715

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.0004270076751709

SELECT *
FROM `roles` 
 Execution Time:0.00043892860412598

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00047111511230469

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00037980079650879

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00034499168395996

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00013113021850586

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00020289421081543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014209747314453

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0001981258392334

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014495849609375

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00040197372436523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003809928894043

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0005490779876709

SELECT *
FROM `roles` 
 Execution Time:0.00044107437133789

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00037312507629395

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019693374633789

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023388862609863

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017309188842773

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00025391578674316

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014901161193848

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00054407119750977

SELECT *
FROM `roles` 
 Execution Time:0.00044894218444824

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00027608871459961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019192695617676

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00028204917907715

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022506713867188

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023293495178223

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00012016296386719

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0001671314239502

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001070499420166

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00042510032653809

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00024700164794922

SELECT *
FROM `roles` 
 Execution Time:0.00041484832763672

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00031495094299316

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020003318786621

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026488304138184

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017905235290527

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023317337036133

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017309188842773

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023007392883301

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017094612121582

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00047802925109863

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00037503242492676

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00036406517028809

SELECT *
FROM `roles` 
 Execution Time:0.00038290023803711

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00048089027404785

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00040197372436523

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0003349781036377

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001838207244873

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023603439331055

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016212463378906

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00022697448730469

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018095970153809

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0005650520324707

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00040102005004883

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00022387504577637

SELECT *
FROM `roles` 
 Execution Time:0.0003809928894043

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00024890899658203

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019288063049316

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0002598762512207

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019502639770508

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00025391578674316

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023293495178223

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00027894973754883

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017285346984863

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00038504600524902

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00032782554626465

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00018310546875

SELECT *
FROM `roles` 
 Execution Time:0.00038814544677734

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00026702880859375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00013899803161621

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0001978874206543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011396408081055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00016593933105469

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011205673217773

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00016403198242188

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00010490417480469

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00035190582275391

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00017094612121582

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00012302398681641

SELECT *
FROM `roles` 
 Execution Time:0.00047087669372559

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00029706954956055

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00022506713867188

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00033307075500488

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023198127746582

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00029206275939941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002589225769043

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00032281875610352

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022602081298828

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00039505958557129

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00029397010803223

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00019311904907227

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00046610832214355

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00028800964355469

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00018191337585449

SELECT *
FROM `roles` 
 Execution Time:0.00038599967956543

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00044107437133789

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00044393539428711

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00030207633972168

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019693374633789

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023794174194336

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018405914306641

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023508071899414

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018095970153809

SELECT *
FROM `roles` 
 Execution Time:0.00038504600524902

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00025105476379395

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019383430480957

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00025296211242676

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017309188842773

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023603439331055

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017189979553223

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00052785873413086

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00031590461730957

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00023794174194336

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003669261932373

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00033402442932129

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00019478797912598

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0004119873046875

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00033903121948242

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00018692016601562

SELECT *
FROM `roles` 
 Execution Time:0.00039911270141602

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00025177001953125

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019407272338867

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023412704467773

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017094612121582

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023293495178223

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001678466796875

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00035905838012695

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00022506713867188

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00018095970153809

SELECT *
FROM `roles` 
 Execution Time:0.00042009353637695

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00035214424133301

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020098686218262

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00035309791564941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00035190582275391

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00033116340637207

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018620491027832

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0002291202545166

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00015020370483398

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00039100646972656

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.0003509521484375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.0003819465637207

SELECT *
FROM `roles` 
 Execution Time:0.00042605400085449

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00039196014404297

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00022482872009277

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00024700164794922

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017595291137695

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023508071899414

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014591217041016

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023293495178223

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014686584472656

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00042605400085449

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00037789344787598

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00021600723266602

SELECT *
FROM `roles` 
 Execution Time:0.0004420280456543

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00024700164794922

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019216537475586

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00029397010803223

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017309188842773

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023412704467773

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016903877258301

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00022721290588379

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011301040649414

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00037813186645508

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00035214424133301

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00017595291137695

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00037097930908203

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00021505355834961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.0001838207244873

SELECT *
FROM `roles` 
 Execution Time:0.00039005279541016

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00025296211242676

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019693374633789

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00030088424682617

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017404556274414

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016999244689941

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0002281665802002

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016593933105469

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00041818618774414

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.0002892017364502

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00024104118347168

SELECT *
FROM `roles` 
 Execution Time:0.00043106079101562

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00034594535827637

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00024700164794922

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00033187866210938

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00027990341186523

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00032782554626465

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023698806762695

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00032711029052734

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023007392883301

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00041818618774414

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00025701522827148

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00026798248291016

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003809928894043

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00024604797363281

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.0001828670501709

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00039410591125488

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00022101402282715

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00018405914306641

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00036311149597168

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00022101402282715

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '219' 
 Execution Time:0.00018501281738281

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00039005279541016

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.0002748966217041

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00018787384033203

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00036978721618652

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00022006034851074

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00018405914306641

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00044012069702148

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00034594535827637

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00019693374633789

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00034904479980469

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00063705444335938

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00021791458129883

SELECT *
FROM `roles` 
 Execution Time:0.00045609474182129

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00029683113098145

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019001960754395

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00027704238891602

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.0003049373626709

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001680850982666

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00012803077697754

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00038313865661621

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00022983551025391

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = 218 
 Execution Time:0.00018596649169922

SELECT *
FROM `roles` 
 Execution Time:0.0004119873046875

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00032806396484375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00025486946105957

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00036811828613281

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002281665802002

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00029706954956055

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002601146697998

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00032711029052734

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022792816162109

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00038504600524902

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00033092498779297

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00019311904907227

SELECT *
FROM `roles` 
 Execution Time:0.00043606758117676

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00032401084899902

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00023603439331055

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00032496452331543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023603439331055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00032496452331543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00026202201843262

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00028705596923828

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019502639770508

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00042295455932617

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00031208992004395

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00036716461181641

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '218' 
 Execution Time:0.00021100044250488

SELECT *
FROM `roles` 
 Execution Time:0.00046586990356445

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00024795532226562

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019192695617676

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0002589225769043

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00015115737915039

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.0001988410949707

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014901161193848

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0001978874206543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001378059387207

SELECT *
FROM `roles` 
 Execution Time:0.00040507316589355

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00035309791564941

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020408630371094

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00034594535827637

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023484230041504

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00024986267089844

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018000602722168

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00022602081298828

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017094612121582

SELECT *
FROM `roles` 
 Execution Time:0.00036406517028809

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00026988983154297

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00017809867858887

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00020790100097656

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011610984802246

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00016999244689941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011205673217773

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00016903877258301

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00010585784912109

SELECT *
FROM `roles` 
 Execution Time:0.0003969669342041

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00035214424133301

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020599365234375

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00027608871459961

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018191337585449

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023102760314941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017094612121582

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00022482872009277

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016307830810547

SELECT *
FROM `roles` 
 Execution Time:0.00036501884460449

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00022506713867188

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00016498565673828

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00023388862609863

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014805793762207

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00020003318786621

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014114379882812

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00019598007202148

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001380443572998

SELECT *
FROM `roles` 
 Execution Time:0.00037503242492676

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00034022331237793

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020503997802734

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026893615722656

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018596649169922

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00024104118347168

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00021100044250488

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00041484832763672

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00058603286743164

SELECT *
FROM `roles` 
 Execution Time:0.00041794776916504

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00055718421936035

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00038886070251465

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00032806396484375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024890899658203

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00024008750915527

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00035309791564941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017690658569336

SELECT *
FROM `roles` 
 Execution Time:0.00041890144348145

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0002589225769043

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019598007202148

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026702880859375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017595291137695

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023412704467773

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00021100044250488

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014209747314453

SELECT *
FROM `roles` 
 Execution Time:0.00050711631774902

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00034594535827637

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00021600723266602

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00027108192443848

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00029492378234863

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00035309791564941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024700164794922

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0003199577331543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024199485778809

SELECT *
FROM `roles` 
 Execution Time:0.00062108039855957

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00029706954956055

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019502639770508

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017285346984863

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023388862609863

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017094612121582

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0002291202545166

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011801719665527

SELECT *
FROM `roles` 
 Execution Time:0.00041890144348145

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00024914741516113

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019478797912598

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0002601146697998

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001680850982666

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00025200843811035

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00031089782714844

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00018095970153809

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011301040649414

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '219' 
 Execution Time:0.00039315223693848

SELECT *
FROM `roles` 
 Execution Time:0.00045895576477051

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00027012825012207

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019311904907227

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026893615722656

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016999244689941

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00021815299987793

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017714500427246

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016403198242188

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '219' 
 Execution Time:0.00037813186645508

SELECT *
FROM `roles` 
 Execution Time:0.0003969669342041

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0004420280456543

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00021195411682129

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026297569274902

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018405914306641

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00022792816162109

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00025677680969238

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00024294853210449

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016999244689941

SELECT *
FROM `roles` 
 Execution Time:0.00041007995605469

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00026893615722656

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001978874206543

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00029420852661133

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022578239440918

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023078918457031

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016999244689941

SELECT *
FROM `roles` 
 Execution Time:0.00037407875061035

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00035786628723145

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020408630371094

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026798248291016

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018811225891113

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00023317337036133

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001678466796875

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.0002281665802002

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016593933105469

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '218' 
 Execution Time:0.00036907196044922

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '219' 
 Execution Time:0.00018000602722168

SELECT *
FROM `roles` 
 Execution Time:0.00046110153198242

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00032305717468262

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020909309387207

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00025391578674316

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00018715858459473

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00030207633972168

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023508071899414

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00039005279541016

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00021195411682129

SELECT *
FROM `roles` 
 Execution Time:0.00043106079101562

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00036501884460449

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020503997802734

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00027108192443848

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00020408630371094

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00053000450134277

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0003049373626709

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00040507316589355

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024580955505371

SELECT *
FROM `roles` 
 Execution Time:0.0004270076751709

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003209114074707

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00028109550476074

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0003211498260498

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002291202545166

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00029301643371582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002291202545166

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00029397010803223

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022602081298828

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '218' 
 Execution Time:0.00042295455932617

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '219' 
 Execution Time:0.00036096572875977

SELECT *
FROM `roles` 
 Execution Time:0.00040006637573242

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00021505355834961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00013303756713867

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00022387504577637

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011205673217773

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00016593933105469

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00015401840209961

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00020098686218262

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '218' 
 Execution Time:0.00037813186645508

DELETE FROM `users`
WHERE `id` = '34' 
 Execution Time:0.00021195411682129

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '219' 
 Execution Time:0.0001990795135498

DELETE FROM `users`
WHERE `id` = '35' 
 Execution Time:0.00013995170593262

DELETE FROM `users`
WHERE `id` IN('218', '219') 
 Execution Time:0.071570158004761

SELECT *
FROM `roles` 
 Execution Time:0.00043606758117676

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00026512145996094

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00025200843811035

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026416778564453

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023484230041504

SELECT *
FROM `users`
WHERE `id` = '218' 
 Execution Time:0.00026583671569824

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00024700164794922

SELECT *
FROM `users`
WHERE `id` = '219' 
 Execution Time:0.00027203559875488

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00017285346984863

SELECT *
FROM `roles` 
 Execution Time:0.00046896934509277

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00023603439331055

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00015902519226074

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00023102760314941

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014591217041016

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00045013427734375

UPDATE `users` SET `first_name` = 'great', `last_name` = 'walkingstreet', `password` = 'e19d5cd5af0378da05f63f891c7467af', `updated_at` = '2016-11-19 11:56:16'
WHERE `id` = '220' 
 Execution Time:0.028815984725952

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00053787231445312

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00039792060852051

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017714500427246

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00034189224243164

SELECT *
FROM `roles` 
 Execution Time:0.00043010711669922

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00029516220092773

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00027799606323242

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00031185150146484

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00026202201843262

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00032281875610352

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023293495178223

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00043082237243652

UPDATE `users` SET `first_name` = 'looping', `last_name` = 'upinroad', `password` = 'e19d5cd5af0378da05f63f891c7467af', `updated_at` = '2016-11-19 11:56:59'
WHERE `id` = '221' 
 Execution Time:0.027202844619751

SELECT *
FROM `users`
WHERE `email` = 'navel@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0004730224609375

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001220703125

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00026702880859375

SELECT *
FROM `roles` 
 Execution Time:0.00044798851013184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00032901763916016

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00024890899658203

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00038385391235352

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002598762512207

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00033211708068848

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00029492378234863

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00035595893859863

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002140998840332

SELECT *
FROM `roles` 
 Execution Time:0.00044012069702148

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003058910369873

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00025200843811035

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00031685829162598

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023794174194336

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00029301643371582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00020098686218262

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00022697448730469

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001678466796875

SELECT *
FROM `roles` 
 Execution Time:0.00042510032653809

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00025606155395508

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019407272338867

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026392936706543

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017285346984863

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00023007392883301

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016880035400391

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00025081634521484

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016689300537109

SELECT *
FROM `roles` 
 Execution Time:0.00038409233093262

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00029587745666504

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00021600723266602

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00034189224243164

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023603439331055

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00041007995605469

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019502639770508

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00023221969604492

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016880035400391

SELECT *
FROM `roles` 
 Execution Time:0.00038695335388184

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00034618377685547

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020885467529297

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.0002751350402832

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017905235290527

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00023508071899414

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017285346984863

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00022196769714355

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001671314239502

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '220' 
 Execution Time:0.00051212310791016

DELETE FROM `users`
WHERE `id` = '3' 
 Execution Time:0.00031399726867676

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '221' 
 Execution Time:0.00026893615722656

DELETE FROM `users`
WHERE `id` = '7' 
 Execution Time:0.00016593933105469

SELECT *
FROM `roles` 
 Execution Time:0.00039291381835938

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00027608871459961

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020003318786621

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00027203559875488

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0002288818359375

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00028705596923828

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00021696090698242

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00032591819763184

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00025701522827148

SELECT *
FROM `roles` 
 Execution Time:0.00037407875061035

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003662109375

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001978874206543

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00025796890258789

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001678466796875

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00023007392883301

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001671314239502

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00021696090698242

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014185905456543

SELECT *
FROM `roles` 
 Execution Time:0.00051593780517578

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0003201961517334

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020217895507812

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00027108192443848

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017285346984863

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00025391578674316

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016903877258301

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00022602081298828

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00011205673217773

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '220' 
 Execution Time:0.00041317939758301

DELETE FROM `users`
WHERE `id` = '36' 
 Execution Time:0.00029206275939941

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '221' 
 Execution Time:0.00023913383483887

DELETE FROM `users`
WHERE `id` = '37' 
 Execution Time:0.00023698806762695

SELECT *
FROM `roles` 
 Execution Time:0.00039410591125488

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.0002439022064209

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00020003318786621

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00028610229492188

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017404556274414

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00023388862609863

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.0001680850982666

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00022697448730469

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016403198242188

SELECT *
FROM `roles` 
 Execution Time:0.00038290023803711

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00041890144348145

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00027108192443848

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00033998489379883

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024604797363281

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00030803680419922

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00024294853210449

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00030899047851562

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00022697448730469

SELECT *
FROM `roles` 
 Execution Time:0.00038409233093262

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00024104118347168

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001990795135498

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00026702880859375

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017499923706055

SELECT *
FROM `users`
WHERE `id` = '220' 
 Execution Time:0.00023317337036133

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00017189979553223

SELECT *
FROM `users`
WHERE `id` = '221' 
 Execution Time:0.00023198127746582

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00016903877258301

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '220' 
 Execution Time:0.00038695335388184

DELETE FROM `vendor_groups`
WHERE `id` = '36' 
 Execution Time:0.11167097091675

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '221' 
 Execution Time:0.00042891502380371

DELETE FROM `vendor_groups`
WHERE `id` = '37' 
 Execution Time:0.040620088577271

DELETE FROM `users`
WHERE `id` IN('220', '221') 
 Execution Time:0.066136837005615

SELECT *
FROM `roles` 
 Execution Time:0.00038909912109375

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00022506713867188

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00012898445129395

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00024008750915527

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00020217895507812

SELECT *
FROM `roles` 
 Execution Time:0.00043296813964844

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00023698806762695

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.0001521110534668

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00023508071899414

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00014781951904297

SELECT *
FROM `roles` 
 Execution Time:0.00045394897460938

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00034093856811523

SELECT *
FROM `vendor_groups`
WHERE `vendor_id` = '19' 
 Execution Time:0.00019311904907227

SELECT *
FROM `users`
WHERE `id` = '214' 
 Execution Time:0.00025796890258789

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00019311904907227

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '214' 
 Execution Time:0.00043082237243652

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '19' 
 Execution Time:0.027588129043579

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '19' 
 Execution Time:0.00038886070251465

SELECT *
FROM `order_items`
WHERE `vendor_id` = '19' 
 Execution Time:0.00036287307739258

SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00064992904663086

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00046396255493164

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00023007392883301

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.00046300888061523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036501884460449

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00044918060302734

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1' 
 Execution Time:0.00035500526428223

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00028800964355469

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1'
AND `item_status` = '3' 
 Execution Time:0.00028085708618164

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1'
AND `item_status` = '4' 
 Execution Time:0.00028705596923828

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00045990943908691

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00027704238891602

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00022482872009277

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00025415420532227

SELECT *
FROM `reviews`
WHERE `model_id` = '1' 
 Execution Time:0.00066804885864258

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '3' 
 Execution Time:0.00042295455932617

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '3' 
 Execution Time:0.00037002563476562

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00031399726867676

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00029706954956055

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00032591819763184

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '3' 
 Execution Time:0.00030088424682617

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '2' 
 Execution Time:0.00031685829162598

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '2' 
 Execution Time:0.00028491020202637

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00028300285339355

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00034093856811523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040888786315918

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00031495094299316

SELECT *
FROM `promo_codes`
WHERE `vendor_id` = '1' 
 Execution Time:0.00054001808166504

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00025105476379395

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1'
AND `item_status` = '3' 
 Execution Time:0.00019598007202148

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1'
AND `item_status` = '4' 
 Execution Time:0.00018692016601562

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00019598007202148

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00017881393432617

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00017786026000977

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00017189979553223

SELECT *
FROM `reviews`
WHERE `model_id` = '1' 
 Execution Time:0.0002448558807373

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '3' 
 Execution Time:0.00024700164794922

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '3' 
 Execution Time:0.00028014183044434

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00031304359436035

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00035905838012695

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00023293495178223

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '3' 
 Execution Time:0.00021505355834961

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '2' 
 Execution Time:0.00020217895507812

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '2' 
 Execution Time:0.00020098686218262

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00020790100097656

SELECT *
FROM `reviews`
WHERE `model_id` = '1'
AND `rating` = '5' 
 Execution Time:0.00021886825561523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00045013427734375

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00037217140197754

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00030303001403809

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00028419494628906

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00030183792114258

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00026488304138184

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00034809112548828

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00025677680969238

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00024604797363281

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00020790100097656

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00034284591674805

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.0002751350402832

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.0002741813659668

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00026822090148926

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00022506713867188

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00022482872009277

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00017786026000977

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00018906593322754

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036787986755371

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026392936706543

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00021815299987793

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00020194053649902

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00014090538024902

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00017213821411133

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00021505355834961

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00019192695617676

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00016903877258301

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.0001530647277832

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.0002131462097168

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00020503997802734

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00015711784362793

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00016283988952637

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00017905235290527

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00018310546875

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00014591217041016

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00015592575073242

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0004420280456543

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00062298774719238

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00027704238891602

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00024008750915527

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00027990341186523

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00043797492980957

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.0003969669342041

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00028300285339355

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00026202201843262

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00024199485778809

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00034284591674805

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00030207633972168

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00023818016052246

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00025320053100586

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00025701522827148

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00024890899658203

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00023508071899414

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00028181076049805

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00042390823364258

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00050997734069824

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.015232086181641

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0086450576782227

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00026607513427734

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00027990341186523

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.0003669261932373

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00027704238891602

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00025606155395508

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00033903121948242

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00037002563476562

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00026702880859375

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.0027720928192139

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.0064141750335693

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00026106834411621

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00024199485778809

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00019407272338867

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.0001978874206543

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00047898292541504

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00045084953308105

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00029516220092773

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00027084350585938

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00027680397033691

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00026106834411621

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00028896331787109

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0002589225769043

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00037002563476562

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00021696090698242

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00029087066650391

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00027108192443848

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00021219253540039

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00023508071899414

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00019693374633789

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00024008750915527

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00020194053649902

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00021505355834961

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037407875061035

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00028395652770996

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00022602081298828

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0001978874206543

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00020313262939453

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00018882751464844

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00022792816162109

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00017809867858887

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00020480155944824

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00023794174194336

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00031685829162598

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00024199485778809

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00017309188842773

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00017189979553223

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00017690658569336

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00019693374633789

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00016593933105469

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00016522407531738

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0004570484161377

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00044083595275879

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00037598609924316

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00042605400085449

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.0002748966217041

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00026488304138184

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00024318695068359

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0002281665802002

SELECT *
FROM `organization_groups`
WHERE `user_id` IS NULL 
 Execution Time:0.00031495094299316

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00032496452331543

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00039815902709961

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00033688545227051

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00020694732666016

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00019097328186035

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00018501281738281

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00021696090698242

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00019192695617676

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00023508071899414

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036787986755371

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003972053527832

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00027799606323242

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00022315979003906

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00041484832763672

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00036907196044922

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00045895576477051

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00034594535827637

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00023198127746582

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.0002439022064209

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00037908554077148

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00058507919311523

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00056099891662598

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00046706199645996

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00047898292541504

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00034093856811523

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00029897689819336

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00026702880859375

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00024700164794922

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00022602081298828

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00020694732666016

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00022697448730469

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00017380714416504

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00018596649169922

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00022101402282715

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00023698806762695

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00017714500427246

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00020194053649902

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003809928894043

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.0003969669342041

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00031185150146484

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00030612945556641

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.0003199577331543

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0003659725189209

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00037598609924316

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.0004270076751709

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.0002589225769043

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00020790100097656

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00022506713867188

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00028109550476074

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00029206275939941

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00029206275939941

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00060486793518066

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00053906440734863

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00038886070251465

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00026702880859375

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00023007392883301

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00023889541625977

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00021195411682129

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.0002288818359375

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00019097328186035

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00019288063049316

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00020313262939453

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.0002601146697998

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00031208992004395

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.0003509521484375

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003969669342041

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00029587745666504

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.000244140625

SELECT *
FROM `users`
WHERE `id` = '210' 
 Execution Time:0.00023794174194336

SELECT *
FROM `organization_groups`
WHERE `user_id` = '210' 
 Execution Time:0.00018191337585449

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00017905235290527

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00019693374633789

SELECT *
FROM `users`
WHERE `id` = '211' 
 Execution Time:0.00020194053649902

SELECT *
FROM `organization_groups`
WHERE `user_id` = '211' 
 Execution Time:0.0001521110534668

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00015878677368164

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00018095970153809

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00025105476379395

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00020694732666016

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00030708312988281

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00018000602722168

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00019502639770508

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00016093254089355

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00015807151794434

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037789344787598

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.0002589225769043

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00021910667419434

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0001981258392334

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00023412704467773

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00021004676818848

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00024318695068359

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00021696090698242

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00016212463378906

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00018000602722168

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00017118453979492

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00016999244689941

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00014805793762207

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00044584274291992

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037908554077148

SELECT *
FROM `order_items`
WHERE `id` = '1' 
 Execution Time:0.00049018859863281

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00051999092102051

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0003809928894043

SELECT *
FROM `organization_groups`
WHERE `user_id` = '166' 
 Execution Time:0.00023484230041504

SELECT *
FROM `user_locations`
WHERE `organization_location_id` = '1' 
 Execution Time:0.039836168289185

SELECT *
FROM `orders`
WHERE `user_id` = '166' 
 Execution Time:0.0003211498260498

SELECT *
FROM `user_vendor_notes`
WHERE `vendor_user_id` = '1' 
 Execution Time:0.010470867156982

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00042486190795898

SELECT *
FROM `order_items`
WHERE `id` = '1' 
 Execution Time:0.00028300285339355

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00025510787963867

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.0002291202545166

SELECT *
FROM `organization_groups`
WHERE `user_id` = '166' 
 Execution Time:0.00018978118896484

SELECT *
FROM `user_locations`
WHERE `organization_location_id` = '1' 
 Execution Time:0.00016999244689941

SELECT *
FROM `orders`
WHERE `user_id` = '166' 
 Execution Time:0.00021004676818848

SELECT *
FROM `user_vendor_notes`
WHERE `vendor_user_id` = '1' 
 Execution Time:0.00036191940307617

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00036311149597168

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00026297569274902

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00024199485778809

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00031018257141113

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00034403800964355

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00047588348388672

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00086688995361328

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00056600570678711

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00076889991760254

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00029206275939941

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00024294853210449

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00024604797363281

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00019097328186035

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00020003318786621

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00037407875061035

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.0005030632019043

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.015742063522339

SELECT *
FROM `promo_codes`
WHERE `product_id` = '2' 
 Execution Time:0.00049519538879395

SELECT *
FROM `products`
WHERE `id` = '5' 
 Execution Time:0.00030899047851562

SELECT *
FROM `promo_codes`
WHERE `product_id` = '5' 
 Execution Time:0.0002739429473877

SELECT *
FROM `products`
WHERE `id` = '6' 
 Execution Time:0.0002291202545166

SELECT *
FROM `promo_codes`
WHERE `product_id` = '6' 
 Execution Time:0.00024676322937012

SELECT *
FROM `products`
WHERE `id` = '8' 
 Execution Time:0.00024008750915527

SELECT *
FROM `promo_codes`
WHERE `product_id` = '8' 
 Execution Time:0.00025296211242676

SELECT *
FROM `products`
WHERE `id` = '7' 
 Execution Time:0.00020194053649902

SELECT *
FROM `promo_codes`
WHERE `product_id` = '7' 
 Execution Time:0.00025606155395508

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00051999092102051

SELECT *
FROM `product_pricings`
WHERE `id` = '16' 
 Execution Time:0.00035715103149414

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00024604797363281

SELECT *
FROM `promo_codes`
WHERE `product_id` = '2' 
 Execution Time:0.00032806396484375

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00049114227294922

SELECT *
FROM `product_pricings`
WHERE `vendor_id` = '1' 
 Execution Time:0.00043106079101562

SELECT *
FROM `products`
WHERE `id` = '2' 
 Execution Time:0.00035500526428223

SELECT *
FROM `promo_codes`
WHERE `product_id` = '2' 
 Execution Time:0.00034403800964355

SELECT *
FROM `products`
WHERE `id` = '5' 
 Execution Time:0.00033688545227051

SELECT *
FROM `promo_codes`
WHERE `product_id` = '5' 
 Execution Time:0.00035309791564941

SELECT *
FROM `products`
WHERE `id` = '6' 
 Execution Time:0.00031089782714844

SELECT *
FROM `promo_codes`
WHERE `product_id` = '6' 
 Execution Time:0.0003211498260498

SELECT *
FROM `products`
WHERE `id` = '8' 
 Execution Time:0.00034093856811523

SELECT *
FROM `promo_codes`
WHERE `product_id` = '8' 
 Execution Time:0.00037503242492676

SELECT *
FROM `products`
WHERE `id` = '7' 
 Execution Time:0.00029611587524414

SELECT *
FROM `promo_codes`
WHERE `product_id` = '7' 
 Execution Time:0.0003211498260498

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.0003819465637207

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00045895576477051

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00034809112548828

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00023603439331055

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00026607513427734

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00026297569274902

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00028800964355469

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00033998489379883

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00027012825012207

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00026202201843262

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.0002751350402832

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.0002589225769043

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00019407272338867

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00024700164794922

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00038599967956543

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00047111511230469

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00036787986755371

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00031399726867676

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00026893615722656

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00029397010803223

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00027799606323242

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00037598609924316

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00027108192443848

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00026321411132812

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00026512145996094

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00026106834411621

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00025010108947754

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00031590461730957

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00039410591125488

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00058197975158691

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00031399726867676

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00030088424682617

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00023412704467773

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00021791458129883

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.0001828670501709

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00020313262939453

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00015592575073242

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00016593933105469

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00016903877258301

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.0001838207244873

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00014519691467285

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00015997886657715

SELECT *
FROM `roles` 
 Execution Time:0.00053501129150391

SELECT *
FROM `users`
WHERE `confirmation_token` = '1g1H2iChMSLfL6GsVw6gKgbubVIuIB' 
 Execution Time:0.00048995018005371

UPDATE `users` SET `confirmation_token` = '', `updated_at` = '2016-11-19 01:18:28'
WHERE `id` = '222' 
 Execution Time:0.039651870727539

SELECT *
FROM `users`
WHERE `email` = 'sparrow@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.0005190372467041

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00033903121948242

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00027012825012207

SELECT *
FROM `privileges`
WHERE `role_id` IS NULL 
 Execution Time:0.00023698806762695

SELECT *
FROM `users`
WHERE `email` = 'sparrow@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00051379203796387

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00038886070251465

SELECT *
FROM `roles`
WHERE `id` IS NULL 
 Execution Time:0.00015902519226074

SELECT *
FROM `privileges`
WHERE `role_id` IS NULL 
 Execution Time:0.00015687942504883

SELECT *
FROM `users`
WHERE `email` = 'harry@webcrafters.in'
AND `password` = 'e19d5cd5af0378da05f63f891c7467af' 
 Execution Time:0.00059413909912109

SELECT *
FROM `users`
WHERE `id` = '128' 
 Execution Time:0.00037813186645508

SELECT *
FROM `roles`
WHERE `id` = '11' 
 Execution Time:0.00031399726867676

SELECT *
FROM `privileges`
WHERE `role_id` = '11' 
 Execution Time:0.022732973098755

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00032305717468262

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00023603439331055

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00021195411682129

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.0001838207244873

SELECT *
FROM `organization_groups`
WHERE `user_id` = '222' 
 Execution Time:0.00015807151794434

SELECT *
FROM `organizations`
WHERE `id` IS NULL 
 Execution Time:0.00014686584472656

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00015902519226074

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00015807151794434

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00013518333435059

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00014901161193848

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00011897087097168

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00012803077697754

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00012493133544922

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.00014591217041016

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00013399124145508

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00018095970153809

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00040006637573242

SELECT *
FROM `order_items`
WHERE `vendor_id` = '1' 
 Execution Time:0.00030708312988281

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00022697448730469

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00022411346435547

SELECT *
FROM `organization_groups`
WHERE `user_id` = '222' 
 Execution Time:0.00015020370483398

SELECT *
FROM `organizations`
WHERE `id` = '1' 
 Execution Time:0.00017404556274414

SELECT *
FROM `orders`
WHERE `id` = '2' 
 Execution Time:0.00015997886657715

SELECT *
FROM `users`
WHERE `id` = '166' 
 Execution Time:0.00018620491027832

SELECT *
FROM `orders`
WHERE `id` = '3' 
 Execution Time:0.00016212463378906

SELECT *
FROM `users`
WHERE `id` = '133' 
 Execution Time:0.00018692016601562

SELECT *
FROM `organization_groups`
WHERE `user_id` = '133' 
 Execution Time:0.00014400482177734

SELECT *
FROM `organizations`
WHERE `id` = '2' 
 Execution Time:0.00015687942504883

SELECT *
FROM `orders`
WHERE `id` = '4' 
 Execution Time:0.00015902519226074

SELECT *
FROM `users`
WHERE `id` = '215' 
 Execution Time:0.0001828670501709

SELECT *
FROM `organization_groups`
WHERE `user_id` = '215' 
 Execution Time:0.00014185905456543

SELECT *
FROM `organizations`
WHERE `id` = '3' 
 Execution Time:0.00015783309936523

SELECT *
FROM `vendor_groups`
WHERE `user_id` = '128' 
 Execution Time:0.00043892860412598

SELECT *
FROM `order_items`
WHERE `id` = '1' 
 Execution Time:0.00027799606323242

SELECT *
FROM `orders`
WHERE `id` = '1' 
 Execution Time:0.00024604797363281

SELECT *
FROM `users`
WHERE `id` = '222' 
 Execution Time:0.00025105476379395

SELECT *
FROM `organization_groups`
WHERE `user_id` = '222' 
 Execution Time:0.00022101402282715

SELECT *
FROM `user_locations`
WHERE `organization_location_id` = '1' 
 Execution Time:0.00016999244689941

SELECT *
FROM `orders`
WHERE `user_id` = '222' 
 Execution Time:0.00019311904907227

SELECT *
FROM `user_vendor_notes`
WHERE `vendor_user_id` = '1' 
 Execution Time:0.00019288063049316

