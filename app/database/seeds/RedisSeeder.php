<?php

class RedisSeeder extends Seeder
{
    protected $pref_key = 'prefectures';
    protected $city_key = 'cities';
    protected $pref_city_key = 'pref_city';
    protected $price_key = 'price';
    protected $option_key = 'option';

    public function run()
    {
        $redis = Redis::connection();
        $prefectures = Config::get('hall.prefectures');
        foreach ($prefectures as $key => $pref) {
            $redis->hset($this->pref_key, $key, $pref);
        }
        $cities = Config::get('hall.cities');
        $redis->del($this->city_key);
        foreach ($cities as $key => $city) {
            // 元々のファイルで0が東京都になっているので整合性を持たせる処理
            $city = $cities[$key];
            $prefName = $redis->hget($this->pref_key, $key);
            // 各都道府県の市区町村をセット
            $cities_key = sprintf('pref:%s', $key);
            //一旦削除
            $redis->del($cities_key);
            foreach($city as $key2 => $value) {
                $redis->rpush($cities_key, $value);
                $city_field = sprintf('pref:%s_city:%s', $prefName, $value);
                $redis->hset($this->pref_city_key, $city_field, $key2);
            }
        }

        // 価格をRedisに保存
        //東京都の価格
        $redis->hset($this->price_key, 'pref:13:tax_excluded', 548000);
        $redis->hset($this->price_key, 'pref:13:tax_included', 591840);

        //福岡県
        $redis->hset($this->price_key, 'pref:40:tax_excluded', 498000);
        $redis->hset($this->price_key, 'pref:40:tax_included', 537840);

        //オプション価格
        $redis->hset($this->option_key, 'monk:tax_excluded', 125000);
        $redis->hset($this->option_key, 'monk:tax_included', 135000);

        $redis->hset($this->option_key, 'flower1:tax_excluded', 32400);
        $redis->hset($this->option_key, 'flower1:tax_included', 34992);

        $redis->hset($this->option_key, 'photo:tax_excluded', 16200);
        $redis->hset($this->option_key, 'photo:tax_included', 17496);

        $redis->hset($this->option_key, 'posthumousName:tax_excluded', 54000);
        $redis->hset($this->option_key, 'posthumousName:tax_included', 58320);
    }

    protected function fixPrefId($beforeId)
    {
        $afterId = 0;
        if ($beforeId === 0) {
            $afterId = 13;
        } elseif ($beforeId <= 5 && $beforeId >= 1) {
            $afterId = $beforeId;
        } elseif ($beforeId <= 12 && $beforeId >= 6) {
            $afterId = $beforeId + 1;
        } else {
            $afterId = $beforeId;
        }
//        $this->command->info(sprintf('%d => %d', $beforeId, $afterId));
        return $afterId;
    }
}
