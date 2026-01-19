<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcountiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subcounties = [
            
            // Mombasa County (id: 1)
            ['name' => 'Changamwe', 'county_id' => 1],
            ['name' => 'Jomvu', 'county_id' => 1],
            ['name' => 'Kisauni', 'county_id' => 1],
            ['name' => 'Nyali', 'county_id' => 1],
            ['name' => 'Likoni', 'county_id' => 1],
            ['name' => 'Mvita', 'county_id' => 1],

            // Kwale County (id: 2)
            ['name' => 'Matuga', 'county_id' => 2],
            ['name' => 'Msambweni', 'county_id' => 2],
            ['name' => 'Kinango', 'county_id' => 2],
            ['name' => 'Lunga Lunga', 'county_id' => 2],

            // Kilifi County (id: 3)
            ['name' => 'Kilifi North', 'county_id' => 3],
            ['name' => 'Kilifi South', 'county_id' => 3],
            ['name' => 'Kaloleni', 'county_id' => 3],
            ['name' => 'Rabai', 'county_id' => 3],
            ['name' => 'Ganze', 'county_id' => 3],
            ['name' => 'Malindi', 'county_id' => 3],
            ['name' => 'Magarini', 'county_id' => 3],

            // Tana River County (id: 4)
            ['name' => 'Bura', 'county_id' => 4],
            ['name' => 'Galole', 'county_id' => 4],
            ['name' => 'Garsen', 'county_id' => 4],

            // Lamu County (id: 5)
            ['name' => 'Lamu East', 'county_id' => 5],
            ['name' => 'Lamu West', 'county_id' => 5],

            // Taita-Taveta County (id: 6)
            ['name' => 'Voi', 'county_id' => 6],
            ['name' => 'Mwatate', 'county_id' => 6],
            ['name' => 'Wundanyi', 'county_id' => 6],
            ['name' => 'Taveta', 'county_id' => 6],

            // Garissa County (id: 7)
            ['name' => 'Garissa Township', 'county_id' => 7],
            ['name' => 'Balambala', 'county_id' => 7],
            ['name' => 'Lagdera', 'county_id' => 7],
            ['name' => 'Dadaab', 'county_id' => 7],
            ['name' => 'Fafi', 'county_id' => 7],
            ['name' => 'Ijara', 'county_id' => 7],

            // Wajir County (id: 8)
            ['name' => 'Wajir East', 'county_id' => 8],
            ['name' => 'Tarbaj', 'county_id' => 8],
            ['name' => 'Wajir West', 'county_id' => 8],
            ['name' => 'Eldas', 'county_id' => 8],
            ['name' => 'Wajir North', 'county_id' => 8],
            ['name' => 'Wajir South', 'county_id' => 8],

            // Mandera County (id: 9)
            ['name' => 'Mandera West', 'county_id' => 9],
            ['name' => 'Banissa', 'county_id' => 9],
            ['name' => 'Mandera North', 'county_id' => 9],
            ['name' => 'Mandera South', 'county_id' => 9],
            ['name' => 'Lafey', 'county_id' => 9],
            ['name' => 'Mandera East', 'county_id' => 9],

            // Marsabit County (id: 10)
            ['name' => 'Moyale', 'county_id' => 10],
            ['name' => 'North Horr', 'county_id' => 10],
            ['name' => 'Saku', 'county_id' => 10],
            ['name' => 'Laisamis', 'county_id' => 10],

            // Isiolo County (id: 11)
            ['name' => 'Isiolo', 'county_id' => 11],
            ['name' => 'Garbatulla', 'county_id' => 11],
            ['name' => 'Merti', 'county_id' => 11],

            // Meru County (id: 12)
            ['name' => 'Imenti North', 'county_id' => 12],
            ['name' => 'Imenti South', 'county_id' => 12],
            ['name' => 'Igembe South', 'county_id' => 12],
            ['name' => 'Igembe Central', 'county_id' => 12],
            ['name' => 'Igembe North', 'county_id' => 12],
            ['name' => 'Tigania West', 'county_id' => 12],
            ['name' => 'Tigania East', 'county_id' => 12],
            ['name' => 'Buuri', 'county_id' => 12],
            ['name' => 'Central Imenti', 'county_id' => 12],

            // Tharaka-Nithi County (id: 13)
            ['name' => 'Tharaka', 'county_id' => 13],
            ['name' => 'Chuka/Igambang\'ombe', 'county_id' => 13],
            ['name' => 'Maara', 'county_id' => 13],

            // Embu County (id: 14)
            ['name' => 'Manyatta', 'county_id' => 14],
            ['name' => 'Runyenjes', 'county_id' => 14],
            ['name' => 'Mbeere South', 'county_id' => 14],
            ['name' => 'Mbeere North', 'county_id' => 14],

            // Kitui County (id: 15)
            ['name' => 'Kitui Central', 'county_id' => 15],
            ['name' => 'Kitui Rural', 'county_id' => 15],
            ['name' => 'Kitui South', 'county_id' => 15],
            ['name' => 'Kitui East', 'county_id' => 15],
            ['name' => 'Kitui West', 'county_id' => 15],
            ['name' => 'Mwingi Central', 'county_id' => 15],
            ['name' => 'Mwingi West', 'county_id' => 15],
            ['name' => 'Mwingi North', 'county_id' => 15],

            // Machakos County (id: 16)
            ['name' => 'Machakos Town', 'county_id' => 16],
            ['name' => 'Mavoko', 'county_id' => 16],
            ['name' => 'Kangundo', 'county_id' => 16],
            ['name' => 'Matungulu', 'county_id' => 16],
            ['name' => 'Kathiani', 'county_id' => 16],
            ['name' => 'Mwala', 'county_id' => 16],
            ['name' => 'Yatta', 'county_id' => 16],
            ['name' => 'Masinga', 'county_id' => 16],

            // Makueni County (id: 17)
            ['name' => 'Makueni', 'county_id' => 17],
            ['name' => 'Kibwezi West', 'county_id' => 17],
            ['name' => 'Kibwezi East', 'county_id' => 17],
            ['name' => 'Kilome', 'county_id' => 17],
            ['name' => 'Kaiti', 'county_id' => 17],
            ['name' => 'Mbooni', 'county_id' => 17],

            // Nyandarua County (id: 18)
            ['name' => 'Kinangop', 'county_id' => 18],
            ['name' => 'Kipipiri', 'county_id' => 18],
            ['name' => 'Ol Kalou', 'county_id' => 18],
            ['name' => 'Ol Jorok', 'county_id' => 18],
            ['name' => 'Ndaragwa', 'county_id' => 18],

            // Nyeri County (id: 19)
            ['name' => 'Tetu', 'county_id' => 19],
            ['name' => 'Kieni', 'county_id' => 19],
            ['name' => 'Mathira', 'county_id' => 19],
            ['name' => 'Othaya', 'county_id' => 19],
            ['name' => 'Mukurweini', 'county_id' => 19],
            ['name' => 'Nyeri Town', 'county_id' => 19],

            // Kirinyaga County (id: 20)
            ['name' => 'Mwea', 'county_id' => 20],
            ['name' => 'Gichugu', 'county_id' => 20],
            ['name' => 'Ndia', 'county_id' => 20],
            ['name' => 'Kirinyaga Central', 'county_id' => 20],

            // Murang'a County (id: 21)
            ['name' => 'Kangema', 'county_id' => 21],
            ['name' => 'Mathioya', 'county_id' => 21],
            ['name' => 'Kiharu', 'county_id' => 21],
            ['name' => 'Kigumo', 'county_id' => 21],
            ['name' => 'Maragwa', 'county_id' => 21],
            ['name' => 'Kandara', 'county_id' => 21],
            ['name' => 'Gatanga', 'county_id' => 21],

            // Kiambu County (id: 22)
            ['name' => 'Gatundu South', 'county_id' => 22],
            ['name' => 'Gatundu North', 'county_id' => 22],
            ['name' => 'Juja', 'county_id' => 22],
            ['name' => 'Thika Town', 'county_id' => 22],
            ['name' => 'Ruiru', 'county_id' => 22],
            ['name' => 'Githunguri', 'county_id' => 22],
            ['name' => 'Kiambu', 'county_id' => 22],
            ['name' => 'Kiambaa', 'county_id' => 22],
            ['name' => 'Kabete', 'county_id' => 22],
            ['name' => 'Kikuyu', 'county_id' => 22],
            ['name' => 'Limuru', 'county_id' => 22],
            ['name' => 'Lari', 'county_id' => 22],

            // Turkana County (id: 23)
            ['name' => 'Turkana North', 'county_id' => 23],
            ['name' => 'Turkana West', 'county_id' => 23],
            ['name' => 'Turkana Central', 'county_id' => 23],
            ['name' => 'Turkana South', 'county_id' => 23],
            ['name' => 'Turkana East', 'county_id' => 23],
            ['name' => 'Loima', 'county_id' => 23],

            // West Pokot County (id: 24)
            ['name' => 'Kapenguria', 'county_id' => 24],
            ['name' => 'Sigor', 'county_id' => 24],
            ['name' => 'Kacheliba', 'county_id' => 24],
            ['name' => 'Pokot South', 'county_id' => 24],

            // Samburu County (id: 25)
            ['name' => 'Samburu West', 'county_id' => 25],
            ['name' => 'Samburu North', 'county_id' => 25],
            ['name' => 'Samburu East', 'county_id' => 25],

            // Trans-Nzoia County (id: 26)
            ['name' => 'Kwanza', 'county_id' => 26],
            ['name' => 'Endebess', 'county_id' => 26],
            ['name' => 'Saboti', 'county_id' => 26],
            ['name' => 'Kiminini', 'county_id' => 26],
            ['name' => 'Cherangany', 'county_id' => 26],

            // Uasin Gishu County (id: 27)
            ['name' => 'Ainabkoi', 'county_id' => 27],
            ['name' => 'Kapseret', 'county_id' => 27],
            ['name' => 'Kesses', 'county_id' => 27],
            ['name' => 'Moiben', 'county_id' => 27],
            ['name' => 'Soy', 'county_id' => 27],
            ['name' => 'Turbo', 'county_id' => 27],

            // Elgeyo-Marakwet County (id: 28)
            ['name' => 'Marakwet East', 'county_id' => 28],
            ['name' => 'Marakwet West', 'county_id' => 28],
            ['name' => 'Keiyo North', 'county_id' => 28],
            ['name' => 'Keiyo South', 'county_id' => 28],

            // Nandi County (id: 29)
            ['name' => 'Tinderet', 'county_id' => 29],
            ['name' => 'Aldai', 'county_id' => 29],
            ['name' => 'Nandi Hills', 'county_id' => 29],
            ['name' => 'Chesumei', 'county_id' => 29],
            ['name' => 'Emgwen', 'county_id' => 29],
            ['name' => 'Mosop', 'county_id' => 29],

            // Baringo County (id: 30)
            ['name' => 'Tiaty', 'county_id' => 30],
            ['name' => 'Baringo North', 'county_id' => 30],
            ['name' => 'Baringo Central', 'county_id' => 30],
            ['name' => 'Baringo South', 'county_id' => 30],
            ['name' => 'Mogotio', 'county_id' => 30],
            ['name' => 'Eldama Ravine', 'county_id' => 30],

            // Laikipia County (id: 31)
            ['name' => 'Laikipia East', 'county_id' => 31],
            ['name' => 'Laikipia West', 'county_id' => 31],
            ['name' => 'Laikipia North', 'county_id' => 31],

            // Nakuru County (id: 32)
            ['name' => 'Nakuru Town East', 'county_id' => 32],
            ['name' => 'Nakuru Town West', 'county_id' => 32],
            ['name' => 'Naivasha', 'county_id' => 32],
            ['name' => 'Gilgil', 'county_id' => 32],
            ['name' => 'Molo', 'county_id' => 32],
            ['name' => 'Njoro', 'county_id' => 32],
            ['name' => 'Rongai', 'county_id' => 32],
            ['name' => 'Subukia', 'county_id' => 32],

            // Narok County (id: 33)
            ['name' => 'Narok North', 'county_id' => 33],
            ['name' => 'Narok East', 'county_id' => 33],
            ['name' => 'Narok South', 'county_id' => 33],
            ['name' => 'Narok West', 'county_id' => 33],
            ['name' => 'Emurua Dikirr', 'county_id' => 33],

            // Kajiado County (id: 34)
            ['name' => 'Kajiado North', 'county_id' => 34],
            ['name' => 'Kajiado Central', 'county_id' => 34],
            ['name' => 'Kajiado East', 'county_id' => 34],
            ['name' => 'Kajiado West', 'county_id' => 34],
            ['name' => 'Kajiado South', 'county_id' => 34],

            // Kericho County (id: 35)
            ['name' => 'Belgut', 'county_id' => 35],
            ['name' => 'Bureti', 'county_id' => 35],
            ['name' => 'Ainamoi', 'county_id' => 35],
            ['name' => 'Kipkelion East', 'county_id' => 35],
            ['name' => 'Kipkelion West', 'county_id' => 35],
            ['name' => 'Soin/Sigowet', 'county_id' => 35],

            // Bomet County (id: 36)
            ['name' => 'Sotik', 'county_id' => 36],
            ['name' => 'Chepalungu', 'county_id' => 36],
            ['name' => 'Bomet East', 'county_id' => 36],
            ['name' => 'Bomet Central', 'county_id' => 36],
            ['name' => 'Konoin', 'county_id' => 36],

            // Kakamega County (id: 37)
            ['name' => 'Lugari', 'county_id' => 37],
            ['name' => 'Likuyani', 'county_id' => 37],
            ['name' => 'Malava', 'county_id' => 37],
            ['name' => 'Lurambi', 'county_id' => 37],
            ['name' => 'Navakholo', 'county_id' => 37],
            ['name' => 'Mumias West', 'county_id' => 37],
            ['name' => 'Mumias East', 'county_id' => 37],
            ['name' => 'Matungu', 'county_id' => 37],
            ['name' => 'Butere', 'county_id' => 37],
            ['name' => 'Khwisero', 'county_id' => 37],
            ['name' => 'Shinyalu', 'county_id' => 37],
            ['name' => 'Ikolomani', 'county_id' => 37],

            // Vihiga County (id: 38)
            ['name' => 'Vihiga', 'county_id' => 38],
            ['name' => 'Sabatia', 'county_id' => 38],
            ['name' => 'Hamisi', 'county_id' => 38],
            ['name' => 'Luanda', 'county_id' => 38],
            ['name' => 'Emuhaya', 'county_id' => 38],

            // Bungoma County (id: 39)
            ['name' => 'Bumula', 'county_id' => 39],
            ['name' => 'Kanduyi', 'county_id' => 39],
            ['name' => 'Webuye East', 'county_id' => 39],
            ['name' => 'Webuye West', 'county_id' => 39],
            ['name' => 'Kimilili', 'county_id' => 39],
            ['name' => 'Tongaren', 'county_id' => 39],
            ['name' => 'Kabuchai', 'county_id' => 39],
            ['name' => 'Mt. Elgon', 'county_id' => 39],
            ['name' => 'Sirisia', 'county_id' => 39],

            // Busia County (id: 40)
            ['name' => 'Teso North', 'county_id' => 40],
            ['name' => 'Teso South', 'county_id' => 40],
            ['name' => 'Nambale', 'county_id' => 40],
            ['name' => 'Matayos', 'county_id' => 40],
            ['name' => 'Butula', 'county_id' => 40],
            ['name' => 'Funyula', 'county_id' => 40],
            ['name' => 'Budalangi', 'county_id' => 40],

            // Siaya County (id: 41)
            ['name' => 'Ugenya', 'county_id' => 41],
            ['name' => 'Ugunja', 'county_id' => 41],
            ['name' => 'Alego Usonga', 'county_id' => 41],
            ['name' => 'Gem', 'county_id' => 41],
            ['name' => 'Bondo', 'county_id' => 41],
            ['name' => 'Rarieda', 'county_id' => 41],

            // Kisumu County (id: 42)
            ['name' => 'Kisumu East', 'county_id' => 42],
            ['name' => 'Kisumu West', 'county_id' => 42],
            ['name' => 'Kisumu Central', 'county_id' => 42],
            ['name' => 'Nyando', 'county_id' => 42],
            ['name' => 'Muhoroni', 'county_id' => 42],
            ['name' => 'Nyakach', 'county_id' => 42],
            ['name' => 'Seme', 'county_id' => 42],

            // Homa Bay County (id: 43)
            ['name' => 'Rangwe', 'county_id' => 43],
            ['name' => 'Homa Bay Town', 'county_id' => 43],
            ['name' => 'Ndhiwa', 'county_id' => 43],
            ['name' => 'Mbita', 'county_id' => 43],
            ['name' => 'Suba North', 'county_id' => 43],
            ['name' => 'Suba South', 'county_id' => 43],
            ['name' => 'Karachuonyo', 'county_id' => 43],
            ['name' => 'Kabondo Kasipul', 'county_id' => 43],

            // Migori County (id: 44)
            ['name' => 'Rongo', 'county_id' => 44],
            ['name' => 'Awendo', 'county_id' => 44],
            ['name' => 'Suna East', 'county_id' => 44],
            ['name' => 'Suna West', 'county_id' => 44],
            ['name' => 'Uriri', 'county_id' => 44],
            ['name' => 'Nyatike', 'county_id' => 44],
            ['name' => 'Kuria West', 'county_id' => 44],
            ['name' => 'Kuria East', 'county_id' => 44],

            // Kisii County (id: 45)
            ['name' => 'Kitutu Chache North', 'county_id' => 45],
            ['name' => 'Kitutu Chache South', 'county_id' => 45],
            ['name' => 'Nyaribari Masaba', 'county_id' => 45],
            ['name' => 'Nyaribari Chache', 'county_id' => 45],
            ['name' => 'Bonchari', 'county_id' => 45],
            ['name' => 'South Mugirango', 'county_id' => 45],
            ['name' => 'Bomachoge Borabu', 'county_id' => 45],
            ['name' => 'Bomachoge Chache', 'county_id' => 45],

            // Nyamira County (id: 46)
            ['name' => 'West Mugirango', 'county_id' => 46],
            ['name' => 'North Mugirango', 'county_id' => 46],
            ['name' => 'Borabu', 'county_id' => 46],
            ['name' => 'Manga', 'county_id' => 46],

            // Nairobi County (id: 47)
            ['name' => 'Westlands', 'county_id' => 47],
            ['name' => 'Dagoretti North', 'county_id' => 47],
            ['name' => 'Dagoretti South', 'county_id' => 47],
            ['name' => 'Langata', 'county_id' => 47],
            ['name' => 'Kibra', 'county_id' => 47],
            ['name' => 'Roysambu', 'county_id' => 47],
            ['name' => 'Kasarani', 'county_id' => 47],
            ['name' => 'Ruaraka', 'county_id' => 47],
            ['name' => 'Embakasi South', 'county_id' => 47],
            ['name' => 'Embakasi North', 'county_id' => 47],
            ['name' => 'Embakasi Central', 'county_id' => 47],
            ['name' => 'Embakasi East', 'county_id' => 47],
            ['name' => 'Embakasi West', 'county_id' => 47],
            ['name' => 'Makadara', 'county_id' => 47],
            ['name' => 'Kamukunji', 'county_id' => 47],
            ['name' => 'Starehe', 'county_id' => 47],
            ['name' => 'Mathare', 'county_id' => 47],
        ];

        DB::table('subcounties')->insert($subcounties);
    }
}
