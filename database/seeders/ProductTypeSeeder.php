<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->truncate();

        $data = [
            ['category_name' => 'AC_REFRIGERATION_REPAIR'],
            ['category_name' => 'ACADEMIC_SOFTWARE'],
            ['category_name' => 'ACCESSORIES'],
            ['category_name' => 'ACCOUNTING'],
            ['category_name' => 'ADVERTISING'],
            ['category_name' => 'AFFILIATED_AUTO_RENTAL'],
            ['category_name' => 'AGENCIES'],
            ['category_name' => 'AGGREGATORS'],
            ['category_name' => 'AGRICULTURAL_COOPERATIVE_FOR_MAIL_ORDER'],
            ['category_name' => 'AIR_CARRIERS_AIRLINES'],
            ['category_name' => 'AIRLINES'],
            ['category_name' => 'AIRPORTS_FLYING_FIELDS'],
            ['category_name' => 'ALCOHOLIC_BEVERAGES'],
            ['category_name' => 'AMUSEMENT_PARKS_CARNIVALS'],
            ['category_name' => 'ANIMATION'],
            ['category_name' => 'ANTIQUES'],
            ['category_name' => 'APPLIANCES'],
            ['category_name' => 'AQUARIAMS_SEAQUARIUMS_DOLPHINARIUMS'],
            ['category_name' => 'ARCHITECTURAL_ENGINEERING_AND_SURVEYING_SERVICES'],
            ['category_name' => 'ART_AND_CRAFT_SUPPLIES'],
            ['category_name' => 'ART_DEALERS_AND_GALLERIES'],
            ['category_name' => 'ARTIFACTS_GRAVE_RELATED_AND_NATIVE_AMERICAN_CRAFTS'],
            ['category_name' => 'ARTS_AND_CRAFTS'],
            ['category_name' => 'ARTS_CRAFTS_AND_COLLECTIBLES'],
            ['category_name' => 'AUDIO_BOOKS'],
            ['category_name' => 'AUTO_ASSOCIATIONS_CLUBS'],
            ['category_name' => 'AUTO_DEALER_USED_ONLY'],
            ['category_name' => 'AUTO_RENTALS'],
            ['category_name' => 'AUTO_SERVICE'],
            ['category_name' => 'AUTOMATED_FUEL_DISPENSERS'],
            ['category_name' => 'AUTOMOBILE_ASSOCIATIONS'],
            ['category_name' => 'AUTOMOTIVE'],
            ['category_name' => 'AUTOMOTIVE_REPAIR_SHOPS_NON_DEALER'],
            ['category_name' => 'AUTOMOTIVE_TOP_AND_BODY_SHOPS'],
            ['category_name' => 'AVIATION'],
            ['category_name' => 'BABIES_CLOTHING_AND_SUPPLIES'],
            ['category_name' => 'BABY'],
            ['category_name' => 'BANDS_ORCHESTRAS_ENTERTAINERS'],
            ['category_name' => 'BARBIES'],
            ['category_name' => 'BATH_AND_BODY'],
            ['category_name' => 'BATTERIES'],
            ['category_name' => 'BEAN_BABIES'],
            ['category_name' => 'BEAUTY'],
            ['category_name' => 'BEAUTY_AND_FRAGRANCES'],
            ['category_name' => 'BED_AND_BATH'],
            ['category_name' => 'BICYCLE_SHOPS_SALES_AND_SERVICE'],
            ['category_name' => 'BICYCLES_AND_ACCESSORIES'],
            ['category_name' => 'BILLIARD_POOL_ESTABLISHMENTS'],
            ['category_name' => 'BOAT_DEALERS'],
            ['category_name' => 'BOAT_RENTALS_AND_LEASING'],
            ['category_name' => 'BOATING_SAILING_AND_ACCESSORIES'],
            ['category_name' => 'BOOKS'],
            ['category_name' => 'BOOKS_AND_MAGAZINES'],
            ['category_name' => 'BOOKS_MANUSCRIPTS'],
            ['category_name' => 'BOOKS_PERIODICALS_AND_NEWSPAPERS'],
            ['category_name' => 'BOWLING_ALLEYS'],
            ['category_name' => 'CAMERAS'],
            ['category_name' => 'CAMERAS_AND_PHOTOGRAPHY'],
            ['category_name' => 'CAMPER_RECREATIONAL_AND_UTILITY_TRAILER_DEALERS'],
            ['category_name' => 'CAMPING_AND_OUTDOORS'],
            ['category_name' => 'CAMPING_AND_SURVIVAL'],
            ['category_name' => 'CAR_AND_TRUCK_DEALERS'],
            ['category_name' => 'CAR_AND_TRUCK_DEALERS_USED_ONLY'],
            ['category_name' => 'COLLECTIBLES'],
            ['category_name' => 'COMPUTER_MAINTENANCE_REPAIR_AND_SERVICES_NOT_ELSEWHERE_CLAS'],
            ['category_name' => 'DIGITAL_GAMES'],
            ['category_name' => 'DEVICES'],
            ['category_name' => 'DESKTOP_PCS'],
            ['category_name' => 'CLOTHING'],
            ['category_name' => 'CLOTHING_ACCESSORIES_AND_SHOES'],
            ['category_name' => 'CLOTHING_RENTAL'],
        ];
        DB::table('product_categories')->insert($data);

    }
}
