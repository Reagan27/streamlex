<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wards = [
            // Mombasa County (County ID: 1)

            // Changamwe Subcounty (Subcounty ID: 1)
            ['name' => 'Port Reitz', 'subcounty_id' => 1],
            ['name' => 'Airport', 'subcounty_id' => 1],
            ['name' => 'Miritini', 'subcounty_id' => 1],
            ['name' => 'Kipevu', 'subcounty_id' => 1],
            ['name' => 'Chaani', 'subcounty_id' => 1],

            // Jomvu Subcounty (Subcounty ID: 2)
            ['name' => 'Mikindani', 'subcounty_id' => 2],
            ['name' => 'Jomvu Kuu', 'subcounty_id' => 2],
            ['name' => 'Magongo', 'subcounty_id' => 2],

            // Kisauni Subcounty (Subcounty ID: 3)
            ['name' => 'Mjambere', 'subcounty_id' => 3],
            ['name' => 'Junda', 'subcounty_id' => 3],
            ['name' => 'Bamburi', 'subcounty_id' => 3],
            ['name' => 'Mwakirunge', 'subcounty_id' => 3],
            ['name' => 'Mtopanga', 'subcounty_id' => 3],
            ['name' => 'Shanzu', 'subcounty_id' => 3],

            // Nyali Subcounty (Subcounty ID: 4)
            ['name' => 'Frere Town', 'subcounty_id' => 4],
            ['name' => 'Ziwa la Ng\'ombe', 'subcounty_id' => 4],
            ['name' => 'Kongowea', 'subcounty_id' => 4],
            ['name' => 'Kadzandani', 'subcounty_id' => 4],
            ['name' => 'Mkomani', 'subcounty_id' => 4],

            // Likoni Subcounty (Subcounty ID: 5)
            ['name' => 'Mtongwe', 'subcounty_id' => 5],
            ['name' => 'Shika Adabu', 'subcounty_id' => 5],
            ['name' => 'Bofu', 'subcounty_id' => 5],
            ['name' => 'Likoni', 'subcounty_id' => 5],
            ['name' => 'Timbwani', 'subcounty_id' => 5],

            // Mvita Subcounty (Subcounty ID: 6)
            ['name' => 'Tudor', 'subcounty_id' => 6],
            ['name' => 'Tononoka', 'subcounty_id' => 6],
            ['name' => 'Shimanzi/Ganjoni', 'subcounty_id' => 6],
            ['name' => 'Majengo', 'subcounty_id' => 6],
            ['name' => 'Ganjoni', 'subcounty_id' => 6],
            ['name' => 'Mji wa Kale/Makadara', 'subcounty_id' => 6],

            // Kwale County (County ID: 2)

            // Matuga Subcounty (Subcounty ID: 7)
            ['name' => 'Kubo South', 'subcounty_id' => 7],
            ['name' => 'Mkongani', 'subcounty_id' => 7],
            ['name' => 'Tiwi', 'subcounty_id' => 7],
            ['name' => 'Waa', 'subcounty_id' => 7],
            ['name' => 'Tsimba/Golini', 'subcounty_id' => 7],

            // Msambweni Subcounty (Subcounty ID: 8)
            ['name' => 'Gombato/Bongwe', 'subcounty_id' => 8],
            ['name' => 'Ukunda', 'subcounty_id' => 8],
            ['name' => 'Kinondo', 'subcounty_id' => 8],
            ['name' => 'Ramisi', 'subcounty_id' => 8],

            // Kinango Subcounty (Subcounty ID: 9)
            ['name' => 'Kinango', 'subcounty_id' => 9],
            ['name' => 'Ndavaya', 'subcounty_id' => 9],
            ['name' => 'Puma', 'subcounty_id' => 9],
            ['name' => 'Kasewe', 'subcounty_id' => 9],
            ['name' => 'Mackinon Road', 'subcounty_id' => 9],

            // Lunga Lunga Subcounty (Subcounty ID: 10)
            ['name' => 'Lunga Lunga', 'subcounty_id' => 10],
            ['name' => 'Vanga', 'subcounty_id' => 10],
            ['name' => 'Dzombo', 'subcounty_id' => 10],
            ['name' => 'Mwereni', 'subcounty_id' => 10],

            // Kilifi County (County ID: 3)

            // Kilifi North Subcounty (Subcounty ID: 11)
            ['name' => 'Mnarani', 'subcounty_id' => 11],
            ['name' => 'Kibarani', 'subcounty_id' => 11],
            ['name' => 'Mtwapa', 'subcounty_id' => 11],
            ['name' => 'Watamu', 'subcounty_id' => 11],
            ['name' => 'Kirepwe', 'subcounty_id' => 11],

            // Kilifi South Subcounty (Subcounty ID: 12)
            ['name' => 'Shimo la Tewa', 'subcounty_id' => 12],
            ['name' => 'Chasimba', 'subcounty_id' => 12],
            ['name' => 'Mwarakaya', 'subcounty_id' => 12],
            ['name' => 'Junju', 'subcounty_id' => 12],
            ['name' => 'Mtepeni', 'subcounty_id' => 12],

            // Kaloleni Subcounty (Subcounty ID: 13)
            ['name' => 'Kaloleni', 'subcounty_id' => 13],
            ['name' => 'Kayafungo', 'subcounty_id' => 13],
            ['name' => 'Mariakani', 'subcounty_id' => 13],
            ['name' => 'Mwanamwinga', 'subcounty_id' => 13],

            // Rabai Subcounty (Subcounty ID: 14)
            ['name' => 'Rabai/Kisurutini', 'subcounty_id' => 14],
            ['name' => 'Mwawesa', 'subcounty_id' => 14],
            ['name' => 'Ruruma', 'subcounty_id' => 14],
            ['name' => 'Mwakirunge', 'subcounty_id' => 14],

            // Ganze Subcounty (Subcounty ID: 15)
            ['name' => 'Ganze', 'subcounty_id' => 15],
            ['name' => 'Bamba', 'subcounty_id' => 15],
            ['name' => 'Sokoke', 'subcounty_id' => 15],
            ['name' => 'Vitengeni', 'subcounty_id' => 15],

            // Malindi Subcounty (Subcounty ID: 16)
            ['name' => 'Malindi Town', 'subcounty_id' => 16],
            ['name' => 'Shella', 'subcounty_id' => 16],
            ['name' => 'Ganda', 'subcounty_id' => 16],
            ['name' => 'Jilore', 'subcounty_id' => 16],
            ['name' => 'Kakuyuni', 'subcounty_id' => 16],

            // Magarini Subcounty (Subcounty ID: 17)
            ['name' => 'Magarini', 'subcounty_id' => 17],
            ['name' => 'Gongoni', 'subcounty_id' => 17],
            ['name' => 'Marafa', 'subcounty_id' => 17],
            ['name' => 'Sabaki', 'subcounty_id' => 17],
            ['name' => 'Adu', 'subcounty_id' => 17],

            // Tana River County (County ID: 4)

            // Bura Subcounty (Subcounty ID: 18)
            ['name' => 'Chewani', 'subcounty_id' => 18],
            ['name' => 'Hirimani', 'subcounty_id' => 18],
            ['name' => 'Madogo', 'subcounty_id' => 18],
            ['name' => 'Nanighi', 'subcounty_id' => 18],
            
            // Galole Subcounty (Subcounty ID: 19)
            ['name' => 'Kinakomba', 'subcounty_id' => 19],
            ['name' => 'Mikinduni', 'subcounty_id' => 19],
            ['name' => 'Wayu', 'subcounty_id' => 19],
            
            // Garsen Subcounty (Subcounty ID: 20)
            ['name' => 'Garsen South', 'subcounty_id' => 20],
            ['name' => 'Garsen Central', 'subcounty_id' => 20],
            ['name' => 'Kipini East', 'subcounty_id' => 20],
            ['name' => 'Kipini West', 'subcounty_id' => 20],

            // Lamu County (County ID: 5)

            // Lamu East Subcounty (Subcounty ID: 21)
            ['name' => 'Faza', 'subcounty_id' => 21],
            ['name' => 'Kiunga', 'subcounty_id' => 21],
            ['name' => 'Basuba', 'subcounty_id' => 21],

            // Lamu West Subcounty (Subcounty ID: 22)
            ['name' => 'Mkomani', 'subcounty_id' => 22],
            ['name' => 'Hindi', 'subcounty_id' => 22],
            ['name' => 'Bahari', 'subcounty_id' => 22],
            ['name' => 'Witu', 'subcounty_id' => 22],

            // Taita-Taveta County (County ID: 6)

            // Voi Subcounty (Subcounty ID: 23)
            ['name' => 'Voi Central', 'subcounty_id' => 23],
            ['name' => 'Kaloleni', 'subcounty_id' => 23],
            ['name' => 'Ngolia', 'subcounty_id' => 23],
            ['name' => 'Sagalla', 'subcounty_id' => 23],
            
            // Mwatate Subcounty (Subcounty ID: 24)
            ['name' => 'Mwatate', 'subcounty_id' => 24],
            ['name' => 'Bura', 'subcounty_id' => 24],
            ['name' => 'Ronge', 'subcounty_id' => 24],
            ['name' => 'Chawia', 'subcounty_id' => 24],

            // Wundanyi Subcounty (Subcounty ID: 25)
            ['name' => 'Wundanyi/Mbale', 'subcounty_id' => 25],
            ['name' => 'Mwanda/Mgange', 'subcounty_id' => 25],
            ['name' => 'Werugha', 'subcounty_id' => 25],
            ['name' => 'Wusi/Kishamba', 'subcounty_id' => 25],

            // Taveta Subcounty (Subcounty ID: 26)
            ['name' => 'Taveta', 'subcounty_id' => 26],
            ['name' => 'Mboghoni', 'subcounty_id' => 26],
            ['name' => 'Chala', 'subcounty_id' => 26],
            ['name' => 'Njukini', 'subcounty_id' => 26],

            // Garissa County (County ID: 7)

            // Garissa Township Subcounty (Subcounty ID: 27)
            ['name' => 'Galbet', 'subcounty_id' => 27],
            ['name' => 'Waberi', 'subcounty_id' => 27],
            ['name' => 'Iftin', 'subcounty_id' => 27],
            ['name' => 'Market', 'subcounty_id' => 27],

            // Balambala Subcounty (Subcounty ID: 28)
            ['name' => 'Balambala', 'subcounty_id' => 28],
            ['name' => 'Saka', 'subcounty_id' => 28],
            ['name' => 'Danyere', 'subcounty_id' => 28],
            ['name' => 'Jilango', 'subcounty_id' => 28],

            // Lagdera Subcounty (Subcounty ID: 29)
            ['name' => 'Modogashe', 'subcounty_id' => 29],
            ['name' => 'Benane', 'subcounty_id' => 29],
            ['name' => 'Goreale', 'subcounty_id' => 29],
            ['name' => 'Sabena', 'subcounty_id' => 29],

            // Dadaab Subcounty (Subcounty ID: 30)
            ['name' => 'Dadaab', 'subcounty_id' => 30],
            ['name' => 'Liboi', 'subcounty_id' => 30],
            ['name' => 'Dertu', 'subcounty_id' => 30],
            ['name' => 'Labasigale', 'subcounty_id' => 30],

            // Fafi Subcounty (Subcounty ID: 31)
            ['name' => 'Bura', 'subcounty_id' => 31],
            ['name' => 'Dekaharia', 'subcounty_id' => 31],
            ['name' => 'Alinjugur', 'subcounty_id' => 31],
            ['name' => 'Hulugho', 'subcounty_id' => 31],

            // Ijara Subcounty (Subcounty ID: 32)
            ['name' => 'Ijara', 'subcounty_id' => 32],
            ['name' => 'Masalani', 'subcounty_id' => 32],
            ['name' => 'Korisa', 'subcounty_id' => 32],
            ['name' => 'Sangailu', 'subcounty_id' => 32],

            // Wajir County (County ID: 8)

            // Wajir East Subcounty (Subcounty ID: 33)
            ['name' => 'Wagberi', 'subcounty_id' => 33],
            ['name' => 'Township', 'subcounty_id' => 33],
            ['name' => 'Barwago', 'subcounty_id' => 33],
            ['name' => 'Khorof Harar', 'subcounty_id' => 33],

            // Tarbaj Subcounty (Subcounty ID: 34)
            ['name' => 'Tarbaj', 'subcounty_id' => 34],
            ['name' => 'Elben', 'subcounty_id' => 34],
            ['name' => 'Sarman', 'subcounty_id' => 34],
            
            // Wajir West Subcounty (Subcounty ID: 35)
            ['name' => 'Griftu', 'subcounty_id' => 35],
            ['name' => 'Habaswein', 'subcounty_id' => 35],
            ['name' => 'Hadado', 'subcounty_id' => 35],
            ['name' => 'Lagathe', 'subcounty_id' => 35],

            // Eldas Subcounty (Subcounty ID: 36)
            ['name' => 'Eldas', 'subcounty_id' => 36],
            ['name' => 'Della', 'subcounty_id' => 36],
            ['name' => 'Elnur', 'subcounty_id' => 36],

            // Wajir North Subcounty (Subcounty ID: 37)
            ['name' => 'Bute', 'subcounty_id' => 37],
            ['name' => 'Danaba', 'subcounty_id' => 37],
            ['name' => 'Gurar', 'subcounty_id' => 37],
            ['name' => 'Korondille', 'subcounty_id' => 37],

            // Wajir South Subcounty (Subcounty ID: 38)
            ['name' => 'Diff', 'subcounty_id' => 38],
            ['name' => 'Dadajabula', 'subcounty_id' => 38],
            ['name' => 'Wagalla', 'subcounty_id' => 38],
            ['name' => 'Qarsa', 'subcounty_id' => 38],

            // Mandera County (County ID: 9)

            // Mandera West Subcounty (Subcounty ID: 39)
            ['name' => 'Takaba South', 'subcounty_id' => 39],
            ['name' => 'Takaba North', 'subcounty_id' => 39],
            ['name' => 'Lagsure', 'subcounty_id' => 39],
            ['name' => 'Dandu', 'subcounty_id' => 39],

            // Banissa Subcounty (Subcounty ID: 40)
            ['name' => 'Banissa', 'subcounty_id' => 40],
            ['name' => 'Malkamari', 'subcounty_id' => 40],
            ['name' => 'Kiliwehiri', 'subcounty_id' => 40],
            ['name' => 'Guba', 'subcounty_id' => 40],

            // Mandera North Subcounty (Subcounty ID: 41)
            ['name' => 'Ashabito', 'subcounty_id' => 41],
            ['name' => 'Guticha', 'subcounty_id' => 41],
            ['name' => 'Rhamu', 'subcounty_id' => 41],
            ['name' => 'Rhamu Dimtu', 'subcounty_id' => 41],

            // Mandera South Subcounty (Subcounty ID: 42)
            ['name' => 'Elwak South', 'subcounty_id' => 42],
            ['name' => 'Elwak North', 'subcounty_id' => 42],
            ['name' => 'Kutulo', 'subcounty_id' => 42],
            ['name' => 'Shimbir Fatuma', 'subcounty_id' => 42],

            // Lafey Subcounty (Subcounty ID: 43)
            ['name' => 'Lafey', 'subcounty_id' => 43],
            ['name' => 'Sala', 'subcounty_id' => 43],
            ['name' => 'Warankara', 'subcounty_id' => 43],
            ['name' => 'Fino', 'subcounty_id' => 43],

            // Mandera East Subcounty (Subcounty ID: 44)
            ['name' => 'Khalalio', 'subcounty_id' => 44],
            ['name' => 'Neboi', 'subcounty_id' => 44],
            ['name' => 'Township', 'subcounty_id' => 44],
            ['name' => 'Shafshafey', 'subcounty_id' => 44],

            // Marsabit County (County ID: 10)

            // Moyale Subcounty (Subcounty ID: 45)
            ['name' => 'Butiye', 'subcounty_id' => 45],
            ['name' => 'Heillu Manyatta', 'subcounty_id' => 45],
            ['name' => 'Golbo', 'subcounty_id' => 45],
            ['name' => 'Sololo', 'subcounty_id' => 45],

            // North Horr Subcounty (Subcounty ID: 46)
            ['name' => 'North Horr', 'subcounty_id' => 46],
            ['name' => 'Turbi', 'subcounty_id' => 46],
            ['name' => 'Maikona', 'subcounty_id' => 46],
            ['name' => 'Illeret', 'subcounty_id' => 46],

            // Saku Subcounty (Subcounty ID: 47)
            ['name' => 'Sagante/Jaldesa', 'subcounty_id' => 47],
            ['name' => 'Marsabit Central', 'subcounty_id' => 47],
            ['name' => 'Karare', 'subcounty_id' => 47],
            ['name' => 'Dirib Gombo', 'subcounty_id' => 47],

            // Laisamis Subcounty (Subcounty ID: 48)
            ['name' => 'Laisamis', 'subcounty_id' => 48],
            ['name' => 'Logologo', 'subcounty_id' => 48],
            ['name' => 'Kargi/South Horr', 'subcounty_id' => 48],
            ['name' => 'Loiyangalani', 'subcounty_id' => 48],

            // Isiolo County (County ID: 11)

            // Isiolo Subcounty (Subcounty ID: 49)
            ['name' => 'Wabera', 'subcounty_id' => 49],
            ['name' => 'Bulla Pesa', 'subcounty_id' => 49],
            ['name' => 'Bulapesa', 'subcounty_id' => 49],
            ['name' => 'Cherab', 'subcounty_id' => 49],

            // Garbatulla Subcounty (Subcounty ID: 50)
            ['name' => 'Garbatulla', 'subcounty_id' => 50],
            ['name' => 'Kinna', 'subcounty_id' => 50],
            ['name' => 'Sericho', 'subcounty_id' => 50],

            // Merti Subcounty (Subcounty ID: 51)
            ['name' => 'Chari', 'subcounty_id' => 51],
            ['name' => 'Ngaremara', 'subcounty_id' => 51],
            ['name' => 'Oldonyiro', 'subcounty_id' => 51],

            // Meru County (County ID: 12)

            // Imenti North Subcounty (Subcounty ID: 52)
            ['name' => 'Municipality', 'subcounty_id' => 52],
            ['name' => 'Nyaki East', 'subcounty_id' => 52],
            ['name' => 'Nyaki West', 'subcounty_id' => 52],
            ['name' => 'Ruiri/Rwarera', 'subcounty_id' => 52],

            // Imenti South Subcounty (Subcounty ID: 53)
            ['name' => 'Mitunguu', 'subcounty_id' => 53],
            ['name' => 'Igoji East', 'subcounty_id' => 53],
            ['name' => 'Igoji West', 'subcounty_id' => 53],
            ['name' => 'Abogeta East', 'subcounty_id' => 53],

            // Igembe South Subcounty (Subcounty ID: 54)
            ['name' => 'Njia', 'subcounty_id' => 54],
            ['name' => 'Kangeta', 'subcounty_id' => 54],
            ['name' => 'Maua', 'subcounty_id' => 54],
            ['name' => 'Akachiu', 'subcounty_id' => 54],

            // Igembe Central Subcounty (Subcounty ID: 55)
            ['name' => 'Igembe Central', 'subcounty_id' => 55],
            ['name' => 'Athiru Ruujine', 'subcounty_id' => 55],
            ['name' => 'Antubetwe Kiongo', 'subcounty_id' => 55],
            ['name' => 'Mikinduri', 'subcounty_id' => 55],

            // Igembe North Subcounty (Subcounty ID: 56)
            ['name' => 'Laare', 'subcounty_id' => 56],
            ['name' => 'Antuambui', 'subcounty_id' => 56],
            ['name' => 'Kiengu', 'subcounty_id' => 56],
            ['name' => 'Ntonyiri', 'subcounty_id' => 56],

            // Tigania West Subcounty (Subcounty ID: 57)
            ['name' => 'Akithii', 'subcounty_id' => 57],
            ['name' => 'Kianjai', 'subcounty_id' => 57],
            ['name' => 'Mbeu', 'subcounty_id' => 57],
            ['name' => 'Ngundune', 'subcounty_id' => 57],

            // Tigania East Subcounty (Subcounty ID: 58)
            ['name' => 'Thangatha', 'subcounty_id' => 58],
            ['name' => 'Mikinduri', 'subcounty_id' => 58],
            ['name' => 'Karama', 'subcounty_id' => 58],
            ['name' => 'Kiguchwa', 'subcounty_id' => 58],

            // Buuri Subcounty (Subcounty ID: 59)
            ['name' => 'Timau', 'subcounty_id' => 59],
            ['name' => 'Kisima', 'subcounty_id' => 59],
            ['name' => 'Kiirua', 'subcounty_id' => 59],
            ['name' => 'Kithirune', 'subcounty_id' => 59],

            // Tharaka-Nithi County (County ID: 13)

            // Tharaka Subcounty (Subcounty ID: 60)
            ['name' => 'Chiakariga', 'subcounty_id' => 60],
            ['name' => 'Marimanti', 'subcounty_id' => 60],
            ['name' => 'Mukothima', 'subcounty_id' => 60],
            ['name' => 'Gatunga', 'subcounty_id' => 60],

            // Chuka/Igambang'ombe Subcounty (Subcounty ID: 61)
            ['name' => 'Magumoni', 'subcounty_id' => 61],
            ['name' => 'Igambangombe', 'subcounty_id' => 61],
            ['name' => 'Karingani', 'subcounty_id' => 61],
            ['name' => 'Mariani', 'subcounty_id' => 61],

            // Maara Subcounty (Subcounty ID: 62)
            ['name' => 'Mitheru', 'subcounty_id' => 62],
            ['name' => 'Muthambi', 'subcounty_id' => 62],
            ['name' => 'Mwimbi', 'subcounty_id' => 62],
            ['name' => 'Ganga', 'subcounty_id' => 62],

            // Embu County (County ID: 14)

            // Manyatta Subcounty (Subcounty ID: 63)
            ['name' => 'Gaturi North', 'subcounty_id' => 63],
            ['name' => 'Kithimu', 'subcounty_id' => 63],
            ['name' => 'Nginda', 'subcounty_id' => 63],
            ['name' => 'Kirimari', 'subcounty_id' => 63],

            // Runyenjes Subcounty (Subcounty ID: 64)
            ['name' => 'Central Ward', 'subcounty_id' => 64],
            ['name' => 'Gaturi South', 'subcounty_id' => 64],
            ['name' => 'Kyeni North', 'subcounty_id' => 64],
            ['name' => 'Kyeni South', 'subcounty_id' => 64],

            // Mbeere South Subcounty (Subcounty ID: 65)
            ['name' => 'Mwea', 'subcounty_id' => 65],
            ['name' => 'Makima', 'subcounty_id' => 65],
            ['name' => 'Kiritiri', 'subcounty_id' => 65],
            ['name' => 'Kiambere', 'subcounty_id' => 65],

            // Mbeere North Subcounty (Subcounty ID: 66)
            ['name' => 'Siakago', 'subcounty_id' => 66],
            ['name' => 'Ndurumori', 'subcounty_id' => 66],
            ['name' => 'Muminji', 'subcounty_id' => 66],
            ['name' => 'Evurore', 'subcounty_id' => 66],

            // Kitui County (County ID: 15)

            // Kitui Central Subcounty (Subcounty ID: 67)
            ['name' => 'Miambani', 'subcounty_id' => 67],
            ['name' => 'Township', 'subcounty_id' => 67],
            ['name' => 'Kyangwithya West', 'subcounty_id' => 67],
            ['name' => 'Kyangwithya East', 'subcounty_id' => 67],

            // Kitui Rural Subcounty (Subcounty ID: 68)
            ['name' => 'Mbitini', 'subcounty_id' => 68],
            ['name' => 'Kwa Mutonga/Kithumula', 'subcounty_id' => 68],
            ['name' => 'Kisasi', 'subcounty_id' => 68],
            ['name' => 'Yatta/Kwa Vonza', 'subcounty_id' => 68],

            // Kitui South Subcounty (Subcounty ID: 69)
            ['name' => 'Mutomo', 'subcounty_id' => 69],
            ['name' => 'Ikutha', 'subcounty_id' => 69],
            ['name' => 'Kanziko', 'subcounty_id' => 69],
            ['name' => 'Athi', 'subcounty_id' => 69],

            // Kitui East Subcounty (Subcounty ID: 70)
            ['name' => 'Zombe/Mwitika', 'subcounty_id' => 70],
            ['name' => 'Nzambani', 'subcounty_id' => 70],
            ['name' => 'Mutha', 'subcounty_id' => 70],
            ['name' => 'Voo/Kyamatu', 'subcounty_id' => 70],

            // Kitui West Subcounty (Subcounty ID: 71)
            ['name' => 'Mutonguni', 'subcounty_id' => 71],
            ['name' => 'Mwingi North', 'subcounty_id' => 71],
            ['name' => 'Kyome/Thaana', 'subcounty_id' => 71],
            ['name' => 'Matinyani', 'subcounty_id' => 71],

            // Mwingi Central Subcounty (Subcounty ID: 72)
            ['name' => 'Waita', 'subcounty_id' => 72],
            ['name' => 'Mwingi', 'subcounty_id' => 72],
            ['name' => 'Migwani', 'subcounty_id' => 72],
            ['name' => 'Nguni', 'subcounty_id' => 72],

            // Mwingi West Subcounty (Subcounty ID: 73)
            ['name' => 'Kyome/Thaana', 'subcounty_id' => 73],
            ['name' => 'Kiomo/Kyethani', 'subcounty_id' => 73],
            ['name' => 'Ngomeni', 'subcounty_id' => 73],
            ['name' => 'Kyome/Thaana', 'subcounty_id' => 73],

            // Mwingi North Subcounty (Subcounty ID: 74)
            ['name' => 'Ngomeni', 'subcounty_id' => 74],
            ['name' => 'Mui', 'subcounty_id' => 74],
            ['name' => 'Kithyoko', 'subcounty_id' => 74],
            ['name' => 'Kivaa', 'subcounty_id' => 74],

            // Machakos County (County ID: 16)

            // Machakos Town Subcounty (Subcounty ID: 75)
            ['name' => 'Machakos Central', 'subcounty_id' => 75],
            ['name' => 'Muvuti/Kiima Kimwe', 'subcounty_id' => 75],
            ['name' => 'Mumbuni North', 'subcounty_id' => 75],
            ['name' => 'Mumbuni South', 'subcounty_id' => 75],

            // Mavoko Subcounty (Subcounty ID: 76)
            ['name' => 'Syokimau/Mlolongo', 'subcounty_id' => 76],
            ['name' => 'Athi River', 'subcounty_id' => 76],
            ['name' => 'Kinanie', 'subcounty_id' => 76],
            ['name' => 'Katani', 'subcounty_id' => 76],

            // Kangundo Subcounty (Subcounty ID: 77)
            ['name' => 'Kangundo North', 'subcounty_id' => 77],
            ['name' => 'Kangundo East', 'subcounty_id' => 77],
            ['name' => 'Kangundo West', 'subcounty_id' => 77],
            ['name' => 'Kangundo South', 'subcounty_id' => 77],

            // Matungulu Subcounty (Subcounty ID: 78)
            ['name' => 'Matungulu North', 'subcounty_id' => 78],
            ['name' => 'Matungulu East', 'subcounty_id' => 78],
            ['name' => 'Matungulu West', 'subcounty_id' => 78],
            ['name' => 'Matungulu South', 'subcounty_id' => 78],

            // Kathiani Subcounty (Subcounty ID: 79)
            ['name' => 'Kathiani Central', 'subcounty_id' => 79],
            ['name' => 'Mitaboni', 'subcounty_id' => 79],
            ['name' => 'Lower Kaewa/Kaani', 'subcounty_id' => 79],
            ['name' => 'Upper Kaewa', 'subcounty_id' => 79],

            // Mwala Subcounty (Subcounty ID: 80)
            ['name' => 'Mwala', 'subcounty_id' => 80],
            ['name' => 'Makutano', 'subcounty_id' => 80],
            ['name' => 'Masii', 'subcounty_id' => 80],
            ['name' => 'Muthetheni', 'subcounty_id' => 80],

            // Yatta Subcounty (Subcounty ID: 81)
            ['name' => 'Matuu', 'subcounty_id' => 81],
            ['name' => 'Kithimani', 'subcounty_id' => 81],
            ['name' => 'Ndithini', 'subcounty_id' => 81],
            ['name' => 'Katangi', 'subcounty_id' => 81],

            // Masinga Subcounty (Subcounty ID: 82)
            ['name' => 'Masinga Central', 'subcounty_id' => 82],
            ['name' => 'Kivaa', 'subcounty_id' => 82],
            ['name' => 'Ekalakala', 'subcounty_id' => 82],
            ['name' => 'Kithyoko', 'subcounty_id' => 82],

            // Makueni County (County ID: 17)

            // Makueni Subcounty (Subcounty ID: 83)
            ['name' => 'Makueni', 'subcounty_id' => 83],
            ['name' => 'Wote', 'subcounty_id' => 83],
            ['name' => 'Kathonzweni', 'subcounty_id' => 83],
            ['name' => 'Kitise/Kithuki', 'subcounty_id' => 83],

            // Kibwezi West Subcounty (Subcounty ID: 84)
            ['name' => 'Makindu', 'subcounty_id' => 84],
            ['name' => 'Nguu/Masumba', 'subcounty_id' => 84],
            ['name' => 'Nguumo', 'subcounty_id' => 84],
            ['name' => 'Kikumbulyu South', 'subcounty_id' => 84],

            // Kibwezi East Subcounty (Subcounty ID: 85)
            ['name' => 'Mtito Andei', 'subcounty_id' => 85],
            ['name' => 'Kambu', 'subcounty_id' => 85],
            ['name' => 'Masongaleni', 'subcounty_id' => 85],
            ['name' => 'Nguumo', 'subcounty_id' => 85],

            // Kilome Subcounty (Subcounty ID: 86)
            ['name' => 'Kasikeu', 'subcounty_id' => 86],
            ['name' => 'Mukaa', 'subcounty_id' => 86],
            ['name' => 'Kikoko', 'subcounty_id' => 86],
            ['name' => 'Kiteta', 'subcounty_id' => 86],

            // Kaiti Subcounty (Subcounty ID: 87)
            ['name' => 'Ukia', 'subcounty_id' => 87],
            ['name' => 'Kilungu', 'subcounty_id' => 87],
            ['name' => 'Ithumba', 'subcounty_id' => 87],
            ['name' => 'Wote', 'subcounty_id' => 87],

            // Mbooni Subcounty (Subcounty ID: 88)
            ['name' => 'Mbooni', 'subcounty_id' => 88],
            ['name' => 'Tawa', 'subcounty_id' => 88],
            ['name' => 'Tulimani', 'subcounty_id' => 88],
            ['name' => 'Kako/Waia', 'subcounty_id' => 88],

            // Nyandarua County (County ID: 18)

            // Kinangop Subcounty (Subcounty ID: 89)
            ['name' => 'Engineer', 'subcounty_id' => 89],
            ['name' => 'Gathara', 'subcounty_id' => 89],
            ['name' => 'North Kinangop', 'subcounty_id' => 89],
            ['name' => 'Murungaru', 'subcounty_id' => 89],
            ['name' => 'Njabini/Kiburu', 'subcounty_id' => 89],

            // Kipipiri Subcounty (Subcounty ID: 90)
            ['name' => 'Kipipiri', 'subcounty_id' => 90],
            ['name' => 'Geta', 'subcounty_id' => 90],
            ['name' => 'Malewa West', 'subcounty_id' => 90],
            ['name' => 'Wanjohi', 'subcounty_id' => 90],

            // Ol Kalou Subcounty (Subcounty ID: 91)
            ['name' => 'Kaimbaga', 'subcounty_id' => 91],
            ['name' => 'Rurii', 'subcounty_id' => 91],
            ['name' => 'Karau', 'subcounty_id' => 91],
            ['name' => 'Mirangine', 'subcounty_id' => 91],

            // Ol Jorok Subcounty (Subcounty ID: 92)
            ['name' => 'Gathanji', 'subcounty_id' => 92],
            ['name' => 'Gatimu', 'subcounty_id' => 92],
            ['name' => 'Weru', 'subcounty_id' => 92],
            ['name' => 'Charagita', 'subcounty_id' => 92],

            // Ndaragwa Subcounty (Subcounty ID: 93)
            ['name' => 'Leshau/Pondo', 'subcounty_id' => 93],
            ['name' => 'Kiriita', 'subcounty_id' => 93],
            ['name' => 'Shamata', 'subcounty_id' => 93],
            ['name' => 'Central', 'subcounty_id' => 93],

            // Nyeri County (County ID: 19)

            // Tetu Subcounty (Subcounty ID: 94)
            ['name' => 'Wamagana', 'subcounty_id' => 94],
            ['name' => 'Kagumo-ini', 'subcounty_id' => 94],
            ['name' => 'Aguthi/Gaaki', 'subcounty_id' => 94],
            ['name' => 'Wamunyoro', 'subcounty_id' => 94],

            // Kieni Subcounty (Subcounty ID: 95)
            ['name' => 'Mwiyogo/Endarasha', 'subcounty_id' => 95],
            ['name' => 'Naromoru/Kiamathaga', 'subcounty_id' => 95],
            ['name' => 'Gakawa', 'subcounty_id' => 95],
            ['name' => 'Mugunda', 'subcounty_id' => 95],

            // Mathira Subcounty (Subcounty ID: 96)
            ['name' => 'Ruguru', 'subcounty_id' => 96],
            ['name' => 'Karatina Town', 'subcounty_id' => 96],
            ['name' => 'Konyu', 'subcounty_id' => 96],
            ['name' => 'Magutu', 'subcounty_id' => 96],

            // Othaya Subcounty (Subcounty ID: 97)
            ['name' => 'Mahiga', 'subcounty_id' => 97],
            ['name' => 'Iria-Ini', 'subcounty_id' => 97],
            ['name' => 'Karima', 'subcounty_id' => 97],
            ['name' => 'Chinga', 'subcounty_id' => 97],

            // Mukurweini Subcounty (Subcounty ID: 98)
            ['name' => 'Gikondi', 'subcounty_id' => 98],
            ['name' => 'Rugi', 'subcounty_id' => 98],
            ['name' => 'Thangathi', 'subcounty_id' => 98],
            ['name' => 'Muthambi', 'subcounty_id' => 98],

            // Nyeri Town Subcounty (Subcounty ID: 99)
            ['name' => 'Rware', 'subcounty_id' => 99],
            ['name' => 'Kamakwa/Mukaro', 'subcounty_id' => 99],
            ['name' => 'Ruringu', 'subcounty_id' => 99],
            ['name' => 'Kiganjo/Mathari', 'subcounty_id' => 99],

            // Kirinyaga County (County ID: 20)

            // Mwea Subcounty (Subcounty ID: 100)
            ['name' => 'Wamumu', 'subcounty_id' => 100],
            ['name' => 'Tebere', 'subcounty_id' => 100],
            ['name' => 'Thiba', 'subcounty_id' => 100],
            ['name' => 'Murinduko', 'subcounty_id' => 100],

            // Gichugu Subcounty (Subcounty ID: 101)
            ['name' => 'Kabare', 'subcounty_id' => 101],
            ['name' => 'Baragwi', 'subcounty_id' => 101],
            ['name' => 'Njukiini', 'subcounty_id' => 101],
            ['name' => 'Karumandi', 'subcounty_id' => 101],

            // Ndia Subcounty (Subcounty ID: 102)
            ['name' => 'Mukure', 'subcounty_id' => 102],
            ['name' => 'Kirimunge', 'subcounty_id' => 102],
            ['name' => 'Gathigiriri', 'subcounty_id' => 102],
            ['name' => 'Kianjege', 'subcounty_id' => 102],

            // Kirinyaga Central Subcounty (Subcounty ID: 103)
            ['name' => 'Kutus', 'subcounty_id' => 103],
            ['name' => 'Inoi', 'subcounty_id' => 103],
            ['name' => 'Kangai', 'subcounty_id' => 103],
            ['name' => 'Kanyekiini', 'subcounty_id' => 103],

            // Murang'a County (County ID: 21)

            // Kangema Subcounty (Subcounty ID: 104)
            ['name' => 'Rwichu', 'subcounty_id' => 104],
            ['name' => 'Gacharage', 'subcounty_id' => 104],
            ['name' => 'Muriranjas', 'subcounty_id' => 104],
            ['name' => 'Kanyenyaini', 'subcounty_id' => 104],

            // Mathioya Subcounty (Subcounty ID: 105)
            ['name' => 'Ichagaki', 'subcounty_id' => 105],
            ['name' => 'Kiru', 'subcounty_id' => 105],
            ['name' => 'Kairo', 'subcounty_id' => 105],
            ['name' => 'Gitugi', 'subcounty_id' => 105],

            // Kiharu Subcounty (Subcounty ID: 106)
            ['name' => 'Wangu', 'subcounty_id' => 106],
            ['name' => 'Mugoiri', 'subcounty_id' => 106],
            ['name' => 'Murarandia', 'subcounty_id' => 106],
            ['name' => 'Mihango', 'subcounty_id' => 106],

            // Kigumo Subcounty (Subcounty ID: 107)
            ['name' => 'Kigumo', 'subcounty_id' => 107],
            ['name' => 'Kimathi', 'subcounty_id' => 107],
            ['name' => 'Muthithi', 'subcounty_id' => 107],
            ['name' => 'Kangari', 'subcounty_id' => 107],

            // Maragwa Subcounty (Subcounty ID: 108)
            ['name' => 'Makuyu', 'subcounty_id' => 108],
            ['name' => 'Kamahuha', 'subcounty_id' => 108],
            ['name' => 'Kambiti', 'subcounty_id' => 108],
            ['name' => 'Mitumbiri', 'subcounty_id' => 108],

            // Kandara Subcounty (Subcounty ID: 109)
            ['name' => 'Gaichanjiru', 'subcounty_id' => 109],
            ['name' => 'Ithiru', 'subcounty_id' => 109],
            ['name' => 'Ngararia', 'subcounty_id' => 109],
            ['name' => 'Kariua', 'subcounty_id' => 109],

            // Gatanga Subcounty (Subcounty ID: 110)
            ['name' => 'Kihumbuini', 'subcounty_id' => 110],
            ['name' => 'Gacharage', 'subcounty_id' => 110],
            ['name' => 'Kirwara', 'subcounty_id' => 110],
            ['name' => 'Mugumoini', 'subcounty_id' => 110],

            // Kiambu County (County ID: 22)

            // Gatundu South Subcounty (Subcounty ID: 111)
            ['name' => 'Kiamwangi', 'subcounty_id' => 111],
            ['name' => 'Kiganjo', 'subcounty_id' => 111],
            ['name' => 'Ndarugo', 'subcounty_id' => 111],
            ['name' => 'Muthurwa', 'subcounty_id' => 111],

            // Gatundu North Subcounty (Subcounty ID: 112)
            ['name' => 'Chania', 'subcounty_id' => 112],
            ['name' => 'Gituamba', 'subcounty_id' => 112],
            ['name' => 'Kiairia', 'subcounty_id' => 112],
            ['name' => 'Wamugongo', 'subcounty_id' => 112],

            // Juja Subcounty (Subcounty ID: 113)
            ['name' => 'Witeithie', 'subcounty_id' => 113],
            ['name' => 'Murera', 'subcounty_id' => 113],
            ['name' => 'Athena', 'subcounty_id' => 113],
            ['name' => 'Kalimoni', 'subcounty_id' => 113],

            // Thika Town Subcounty (Subcounty ID: 114)
            ['name' => 'Kamenu', 'subcounty_id' => 114],
            ['name' => 'Hospital', 'subcounty_id' => 114],
            ['name' => 'Township', 'subcounty_id' => 114],
            ['name' => 'Gatundu', 'subcounty_id' => 114],

            // Ruiru Subcounty (Subcounty ID: 115)
            ['name' => 'Githurai', 'subcounty_id' => 115],
            ['name' => 'Kahawa Sukari', 'subcounty_id' => 115],
            ['name' => 'Kahawa Wendani', 'subcounty_id' => 115],
            ['name' => 'Mwihoko', 'subcounty_id' => 115],

            // Githunguri Subcounty (Subcounty ID: 116)
            ['name' => 'Githunguri', 'subcounty_id' => 116],
            ['name' => 'Ikinu', 'subcounty_id' => 116],
            ['name' => 'Komothai', 'subcounty_id' => 116],
            ['name' => 'Ngewa', 'subcounty_id' => 116],

            // Kiambu Subcounty (Subcounty ID: 117)
            ['name' => 'Tinganga', 'subcounty_id' => 117],
            ['name' => 'Ndumberi', 'subcounty_id' => 117],
            ['name' => 'Riabai', 'subcounty_id' => 117],
            ['name' => 'Kanunga', 'subcounty_id' => 117],

            // Kiambaa Subcounty (Subcounty ID: 118)
            ['name' => 'Karuri', 'subcounty_id' => 118],
            ['name' => 'Ndenderu', 'subcounty_id' => 118],
            ['name' => 'Muchatha', 'subcounty_id' => 118],
            ['name' => 'Kihara', 'subcounty_id' => 118],

            // Kabete Subcounty (Subcounty ID: 119)
            ['name' => 'Kabete', 'subcounty_id' => 119],
            ['name' => 'Gitaru', 'subcounty_id' => 119],
            ['name' => 'Muguga', 'subcounty_id' => 119],
            ['name' => 'Nyathuna', 'subcounty_id' => 119],

            // Kikuyu Subcounty (Subcounty ID: 120)
            ['name' => 'Kikuyu', 'subcounty_id' => 120],
            ['name' => 'Sigona', 'subcounty_id' => 120],
            ['name' => 'Zambezi', 'subcounty_id' => 120],
            ['name' => 'Thogoto', 'subcounty_id' => 120],

            // Limuru Subcounty (Subcounty ID: 121)
            ['name' => 'Limuru', 'subcounty_id' => 121],
            ['name' => 'Tigoni', 'subcounty_id' => 121],
            ['name' => 'Ngecha', 'subcounty_id' => 121],
            ['name' => 'Bibirioni', 'subcounty_id' => 121],

            // Lari Subcounty (Subcounty ID: 122)
            ['name' => 'Kijabe', 'subcounty_id' => 122],
            ['name' => 'Nyanduma', 'subcounty_id' => 122],
            ['name' => 'Kamwangi', 'subcounty_id' => 122],
            ['name' => 'Gatamaiyu', 'subcounty_id' => 122],

            // Turkana County (County ID: 23)

            // Turkana North Subcounty (Subcounty ID: 123)
            ['name' => 'Kaikor', 'subcounty_id' => 123],
            ['name' => 'Nakinomet', 'subcounty_id' => 123],
            ['name' => 'Lodwar Town', 'subcounty_id' => 123],
            ['name' => 'Kerio Delta', 'subcounty_id' => 123],

            // Turkana West Subcounty (Subcounty ID: 124)
            ['name' => 'Lokichoggio', 'subcounty_id' => 124],
            ['name' => 'Nadapal', 'subcounty_id' => 124],
            ['name' => 'Kakuma', 'subcounty_id' => 124],
            ['name' => 'Lopur', 'subcounty_id' => 124],

            // Turkana Central Subcounty (Subcounty ID: 125)
            ['name' => 'Lodwar', 'subcounty_id' => 125],
            ['name' => 'Kanamkemer', 'subcounty_id' => 125],
            ['name' => 'Kalokol', 'subcounty_id' => 125],
            ['name' => 'Kerio', 'subcounty_id' => 125],

            // Turkana South Subcounty (Subcounty ID: 126)
            ['name' => 'Lokichar', 'subcounty_id' => 126],
            ['name' => 'Katilu', 'subcounty_id' => 126],
            ['name' => 'Kainuk', 'subcounty_id' => 126],
            ['name' => 'Kaputir', 'subcounty_id' => 126],

            // Turkana East Subcounty (Subcounty ID: 127)
            ['name' => 'Lokori/Kochodin', 'subcounty_id' => 127],
            ['name' => 'Kaaleng/Kaikor', 'subcounty_id' => 127],
            ['name' => 'Katilia', 'subcounty_id' => 127],
            ['name' => 'Lobei/Kotaruk', 'subcounty_id' => 127],

            // Loima Subcounty (Subcounty ID: 128)
            ['name' => 'Lorugum', 'subcounty_id' => 128],
            ['name' => 'Loima', 'subcounty_id' => 128],
            ['name' => 'Turkwel', 'subcounty_id' => 128],
            ['name' => 'Pochalla', 'subcounty_id' => 128],

            // West Pokot County (County ID: 24)

            // Kapenguria Subcounty (Subcounty ID: 129)
            ['name' => 'Kapenguria', 'subcounty_id' => 129],
            ['name' => 'Mnagei', 'subcounty_id' => 129],
            ['name' => 'Sook', 'subcounty_id' => 129],
            ['name' => 'Lelan', 'subcounty_id' => 129],

            // Sigor Subcounty (Subcounty ID: 130)
            ['name' => 'Siyoi', 'subcounty_id' => 130],
            ['name' => 'Sekerr', 'subcounty_id' => 130],
            ['name' => 'Masol', 'subcounty_id' => 130],
            ['name' => 'Weiwei', 'subcounty_id' => 130],

            // Kacheliba Subcounty (Subcounty ID: 131)
            ['name' => 'Kacheliba', 'subcounty_id' => 131],
            ['name' => 'Suam', 'subcounty_id' => 131],
            ['name' => 'Kasei', 'subcounty_id' => 131],
            ['name' => 'Kodich', 'subcounty_id' => 131],

            // Pokot South Subcounty (Subcounty ID: 132)
            ['name' => 'Tapach', 'subcounty_id' => 132],
            ['name' => 'Batei', 'subcounty_id' => 132],
            ['name' => 'Chepareria', 'subcounty_id' => 132],
            ['name' => 'Endugh', 'subcounty_id' => 132],

            // Samburu County (County ID: 25)

            // Samburu West Subcounty (Subcounty ID: 133)
            ['name' => 'Poro', 'subcounty_id' => 133],
            ['name' => 'Suguta', 'subcounty_id' => 133],
            ['name' => 'Maralal', 'subcounty_id' => 133],
            ['name' => 'Loosuk', 'subcounty_id' => 133],

            // Samburu North Subcounty (Subcounty ID: 134)
            ['name' => 'Baragoi', 'subcounty_id' => 134],
            ['name' => 'Nyiro', 'subcounty_id' => 134],
            ['name' => 'Ndoto', 'subcounty_id' => 134],
            ['name' => 'Elbarta', 'subcounty_id' => 134],

            // Samburu East Subcounty (Subcounty ID: 135)
            ['name' => 'Wamba North', 'subcounty_id' => 135],
            ['name' => 'Wamba West', 'subcounty_id' => 135],
            ['name' => 'Waso', 'subcounty_id' => 135],
            ['name' => 'Lodokejek', 'subcounty_id' => 135],

            // Trans-Nzoia County (County ID: 26)

            // Kwanza Subcounty (Subcounty ID: 136)
            ['name' => 'Kapomboi', 'subcounty_id' => 136],
            ['name' => 'Kwanza', 'subcounty_id' => 136],
            ['name' => 'Keiyo', 'subcounty_id' => 136],
            ['name' => 'Bidii', 'subcounty_id' => 136],

            // Endebess Subcounty (Subcounty ID: 137)
            ['name' => 'Matumbei', 'subcounty_id' => 137],
            ['name' => 'Endebess', 'subcounty_id' => 137],
            ['name' => 'Chepchoina', 'subcounty_id' => 137],
            ['name' => 'Kimoson', 'subcounty_id' => 137],

            // Saboti Subcounty (Subcounty ID: 138)
            ['name' => 'Saboti', 'subcounty_id' => 138],
            ['name' => 'Kiminini', 'subcounty_id' => 138],
            ['name' => 'Kitalale', 'subcounty_id' => 138],
            ['name' => 'Matisi', 'subcounty_id' => 138],

            // Kiminini Subcounty (Subcounty ID: 139)
            ['name' => 'Kiminini', 'subcounty_id' => 139],
            ['name' => 'Hospital', 'subcounty_id' => 139],
            ['name' => 'Nabiswa', 'subcounty_id' => 139],
            ['name' => 'Waitaluk', 'subcounty_id' => 139],

            // Cherangany Subcounty (Subcounty ID: 140)
            ['name' => 'Cherangany', 'subcounty_id' => 140],
            ['name' => 'Kaplamai', 'subcounty_id' => 140],
            ['name' => 'Sitatunga', 'subcounty_id' => 140],
            ['name' => 'Moiben/Kuserwo', 'subcounty_id' => 140],

            // Uasin Gishu County (County ID: 27)

            // Ainabkoi Subcounty (Subcounty ID: 141)
            ['name' => 'Kapsoya', 'subcounty_id' => 141],
            ['name' => 'Racecourse', 'subcounty_id' => 141],
            ['name' => 'Kipkorgot', 'subcounty_id' => 141],
            ['name' => 'Cheptiret/Kipchamo', 'subcounty_id' => 141],

            // Kapseret Subcounty (Subcounty ID: 142)
            ['name' => 'Langas', 'subcounty_id' => 142],
            ['name' => 'Simat/Kapsaret', 'subcounty_id' => 142],
            ['name' => 'Megun', 'subcounty_id' => 142],
            ['name' => 'Cheplaskei', 'subcounty_id' => 142],

            // Kesses Subcounty (Subcounty ID: 143)
            ['name' => 'Cheptiret/Kipchamo', 'subcounty_id' => 143],
            ['name' => 'Kesses', 'subcounty_id' => 143],
            ['name' => 'Tarakwa', 'subcounty_id' => 143],
            ['name' => 'Racecourse', 'subcounty_id' => 143],

            // Moiben Subcounty (Subcounty ID: 144)
            ['name' => 'Karuna/Meibeki', 'subcounty_id' => 144],
            ['name' => 'Moiben', 'subcounty_id' => 144],
            ['name' => 'Sergoit', 'subcounty_id' => 144],
            ['name' => 'Tembelio', 'subcounty_id' => 144],

            // Soy Subcounty (Subcounty ID: 145)
            ['name' => 'Soy', 'subcounty_id' => 145],
            ['name' => 'Ziwa', 'subcounty_id' => 145],
            ['name' => 'Moi\'s Bridge', 'subcounty_id' => 145],
            ['name' => 'Kipsomba', 'subcounty_id' => 145],

            // Turbo Subcounty (Subcounty ID: 146)
            ['name' => 'Kamagut', 'subcounty_id' => 146],
            ['name' => 'Tapsagoi', 'subcounty_id' => 146],
            ['name' => 'Huruma', 'subcounty_id' => 146],
            ['name' => 'Ngenyilel', 'subcounty_id' => 146],

            // Elgeyo-Marakwet County (County ID: 28)

            // Marakwet East Subcounty (Subcounty ID: 147)
            ['name' => 'Kapyego', 'subcounty_id' => 147],
            ['name' => 'Embobut/Embolot', 'subcounty_id' => 147],
            ['name' => 'Endo', 'subcounty_id' => 147],
            ['name' => 'Sambirir', 'subcounty_id' => 147],

            // Marakwet West Subcounty (Subcounty ID: 148)
            ['name' => 'Kapsowar', 'subcounty_id' => 148],
            ['name' => 'Arror', 'subcounty_id' => 148],
            ['name' => 'Chebiemit', 'subcounty_id' => 148],
            ['name' => 'Moiben/Kuserwo', 'subcounty_id' => 148],

            // Keiyo North Subcounty (Subcounty ID: 149)
            ['name' => 'Kapchemutwa', 'subcounty_id' => 149],
            ['name' => 'Kamariny', 'subcounty_id' => 149],
            ['name' => 'Kaptarakwa', 'subcounty_id' => 149],
            ['name' => 'Metkei', 'subcounty_id' => 149],

            // Keiyo South Subcounty (Subcounty ID: 150)
            ['name' => 'Chepkorio', 'subcounty_id' => 150],
            ['name' => 'Soy North', 'subcounty_id' => 150],
            ['name' => 'Kabiemit', 'subcounty_id' => 150],
            ['name' => 'Kiptuilong', 'subcounty_id' => 150],

            // Nandi County (County ID: 29)

            // Tinderet Subcounty (Subcounty ID: 151)
            ['name' => 'Songhor/Soba', 'subcounty_id' => 151],
            ['name' => 'Chemase/Chemelil', 'subcounty_id' => 151],
            ['name' => 'Tindiret', 'subcounty_id' => 151],
            ['name' => 'Kapsimotwo', 'subcounty_id' => 151],

            // Aldai Subcounty (Subcounty ID: 152)
            ['name' => 'Kabwareng', 'subcounty_id' => 152],
            ['name' => 'Kaptumo', 'subcounty_id' => 152],
            ['name' => 'Kaptel/Kamoiywo', 'subcounty_id' => 152],
            ['name' => 'Koyo/Ndurio', 'subcounty_id' => 152],

            // Nandi Hills Subcounty (Subcounty ID: 153)
            ['name' => 'Nandi Hills', 'subcounty_id' => 153],
            ['name' => 'Chepkunyuk', 'subcounty_id' => 153],
            ['name' => 'Kipkaren', 'subcounty_id' => 153],
            ['name' => 'Ollessos', 'subcounty_id' => 153],

            // Chesumei Subcounty (Subcounty ID: 154)
            ['name' => 'Kosirai', 'subcounty_id' => 154],
            ['name' => 'Kiptuiya', 'subcounty_id' => 154],
            ['name' => 'Kaptel', 'subcounty_id' => 154],
            ['name' => 'Kaplamai', 'subcounty_id' => 154],

            // Emgwen Subcounty (Subcounty ID: 155)
            ['name' => 'Kilibwoni', 'subcounty_id' => 155],
            ['name' => 'Kapkangani', 'subcounty_id' => 155],
            ['name' => 'Cheptarit', 'subcounty_id' => 155],
            ['name' => 'Kapsabet', 'subcounty_id' => 155],

            // Mosop Subcounty (Subcounty ID: 156)
            ['name' => 'Lelmokwo/Ngechek', 'subcounty_id' => 156],
            ['name' => 'Kabisaga', 'subcounty_id' => 156],
            ['name' => 'Ndalat', 'subcounty_id' => 156],
            ['name' => 'Kipkaren Salient', 'subcounty_id' => 156],

            // Baringo County (County ID: 30)

            // Tiaty Subcounty (Subcounty ID: 157)
            ['name' => 'Tangulbei/Korossi', 'subcounty_id' => 157],
            ['name' => 'Silale', 'subcounty_id' => 157],
            ['name' => 'Ribkwo', 'subcounty_id' => 157],
            ['name' => 'Loiyamorok', 'subcounty_id' => 157],

            // Baringo North Subcounty (Subcounty ID: 158)
            ['name' => 'Kabartonjo', 'subcounty_id' => 158],
            ['name' => 'Saimo/Kipsaraman', 'subcounty_id' => 158],
            ['name' => 'Barwessa', 'subcounty_id' => 158],
            ['name' => 'Saimo Soi', 'subcounty_id' => 158],

            // Baringo Central Subcounty (Subcounty ID: 159)
            ['name' => 'Kabarnet', 'subcounty_id' => 159],
            ['name' => 'Tenges', 'subcounty_id' => 159],
            ['name' => 'Ewalel/Chapchap', 'subcounty_id' => 159],
            ['name' => 'Kapropita', 'subcounty_id' => 159],

            // Baringo South Subcounty (Subcounty ID: 160)
            ['name' => 'Marigat', 'subcounty_id' => 160],
            ['name' => 'Ilangoi', 'subcounty_id' => 160],
            ['name' => 'Mochongoi', 'subcounty_id' => 160],
            ['name' => 'Mukutani', 'subcounty_id' => 160],

            // Mogotio Subcounty (Subcounty ID: 161)
            ['name' => 'Mogotio', 'subcounty_id' => 161],
            ['name' => 'Emining', 'subcounty_id' => 161],
            ['name' => 'Kisanana', 'subcounty_id' => 161],
            ['name' => 'Sosian', 'subcounty_id' => 161],

            // Eldama Ravine Subcounty (Subcounty ID: 162)
            ['name' => 'Lembus', 'subcounty_id' => 162],
            ['name' => 'Lembus Perkerra', 'subcounty_id' => 162],
            ['name' => 'Ravine', 'subcounty_id' => 162],
            ['name' => 'Mumberes/Maji Mazuri', 'subcounty_id' => 162],

            // Laikipia County (County ID: 31)

            // Laikipia East Subcounty (Subcounty ID: 163)
            ['name' => 'Thingithu', 'subcounty_id' => 163],
            ['name' => 'Nanyuki', 'subcounty_id' => 163],
            ['name' => 'Tigithi', 'subcounty_id' => 163],
            ['name' => 'Ngobit', 'subcounty_id' => 163],

            // Laikipia West Subcounty (Subcounty ID: 164)
            ['name' => 'Ol-Moran', 'subcounty_id' => 164],
            ['name' => 'Salama', 'subcounty_id' => 164],
            ['name' => 'Rumuruti', 'subcounty_id' => 164],
            ['name' => 'Githiga', 'subcounty_id' => 164],

            // Laikipia North Subcounty (Subcounty ID: 165)
            ['name' => 'Mukogodo East', 'subcounty_id' => 165],
            ['name' => 'Mukogodo West', 'subcounty_id' => 165],
            ['name' => 'Sosian', 'subcounty_id' => 165],
            ['name' => 'Segera', 'subcounty_id' => 165],

            // Nakuru County (County ID: 32)

            // Nakuru Town East Subcounty (Subcounty ID: 166)
            ['name' => 'Biashara', 'subcounty_id' => 166],
            ['name' => 'Flamingo', 'subcounty_id' => 166],
            ['name' => 'Kivumbini', 'subcounty_id' => 166],
            ['name' => 'Menengai', 'subcounty_id' => 166],

            // Nakuru Town West Subcounty (Subcounty ID: 167)
            ['name' => 'London', 'subcounty_id' => 167],
            ['name' => 'Kaptembwa', 'subcounty_id' => 167],
            ['name' => 'Rhoda', 'subcounty_id' => 167],
            ['name' => 'Shabaab', 'subcounty_id' => 167],

            // Naivasha Subcounty (Subcounty ID: 168)
            ['name' => 'Lakeview', 'subcounty_id' => 168],
            ['name' => 'Maai Mahiu', 'subcounty_id' => 168],
            ['name' => 'Hell\'s Gate', 'subcounty_id' => 168],
            ['name' => 'Naivasha East', 'subcounty_id' => 168],

            // Gilgil Subcounty (Subcounty ID: 169)
            ['name' => 'Gilgil', 'subcounty_id' => 169],
            ['name' => 'Elementaita', 'subcounty_id' => 169],
            ['name' => 'Murindat', 'subcounty_id' => 169],
            ['name' => 'Malewa West', 'subcounty_id' => 169],

            // Molo Subcounty (Subcounty ID: 170)
            ['name' => 'Molo', 'subcounty_id' => 170],
            ['name' => 'Turi', 'subcounty_id' => 170],
            ['name' => 'Elburgon', 'subcounty_id' => 170],
            ['name' => 'Mariashoni', 'subcounty_id' => 170],

            // Njoro Subcounty (Subcounty ID: 171)
            ['name' => 'Njoro', 'subcounty_id' => 171],
            ['name' => 'Mau Narok', 'subcounty_id' => 171],
            ['name' => 'Mauche', 'subcounty_id' => 171],
            ['name' => 'Lare', 'subcounty_id' => 171],

            // Rongai Subcounty (Subcounty ID: 172)
            ['name' => 'Visoi', 'subcounty_id' => 172],
            ['name' => 'Solai', 'subcounty_id' => 172],
            ['name' => 'Menengai West', 'subcounty_id' => 172],
            ['name' => 'Soin', 'subcounty_id' => 172],

            // Subukia Subcounty (Subcounty ID: 173)
            ['name' => 'Kabazi', 'subcounty_id' => 173],
            ['name' => 'Waseges', 'subcounty_id' => 173],
            ['name' => 'Subukia', 'subcounty_id' => 173],
            ['name' => 'Gituamba', 'subcounty_id' => 173],

            // Narok County (County ID: 33)

            // Narok North Subcounty (Subcounty ID: 174)
            ['name' => 'Olorropil', 'subcounty_id' => 174],
            ['name' => 'Olokurto', 'subcounty_id' => 174],
            ['name' => 'Narok Town', 'subcounty_id' => 174],
            ['name' => 'Nkareta', 'subcounty_id' => 174],

            // Narok East Subcounty (Subcounty ID: 175)
            ['name' => 'Melili', 'subcounty_id' => 175],
            ['name' => 'Ilmotiok', 'subcounty_id' => 175],
            ['name' => 'Mosiro', 'subcounty_id' => 175],
            ['name' => 'Suswa', 'subcounty_id' => 175],

            // Narok South Subcounty (Subcounty ID: 176)
            ['name' => 'Ololulunga', 'subcounty_id' => 176],
            ['name' => 'Melelo', 'subcounty_id' => 176],
            ['name' => 'Loita', 'subcounty_id' => 176],
            ['name' => 'Sogoo', 'subcounty_id' => 176],

            // Narok West Subcounty (Subcounty ID: 177)
            ['name' => 'Mara', 'subcounty_id' => 177],
            ['name' => 'Naikarra', 'subcounty_id' => 177],
            ['name' => 'Siana', 'subcounty_id' => 177],
            ['name' => 'Ildamat', 'subcounty_id' => 177],

            // Emurua Dikirr Subcounty (Subcounty ID: 178)
            ['name' => 'Kimintet', 'subcounty_id' => 178],
            ['name' => 'Oloirien', 'subcounty_id' => 178],
            ['name' => 'Ilkerin', 'subcounty_id' => 178],
            ['name' => 'Ololmasani', 'subcounty_id' => 178],

            // Kajiado County (County ID: 34)

            // Kajiado North Subcounty (Subcounty ID: 179)
            ['name' => 'Olkeri', 'subcounty_id' => 179],
            ['name' => 'Nkaimurunya', 'subcounty_id' => 179],
            ['name' => 'Ngong', 'subcounty_id' => 179],
            ['name' => 'Ongata Rongai', 'subcounty_id' => 179],

            // Kajiado Central Subcounty (Subcounty ID: 180)
            ['name' => 'Purko', 'subcounty_id' => 180],
            ['name' => 'Ilbisil', 'subcounty_id' => 180],
            ['name' => 'Namanga', 'subcounty_id' => 180],
            ['name' => 'Matapato North', 'subcounty_id' => 180],

            // Kajiado East Subcounty (Subcounty ID: 181)
            ['name' => 'Kitengela', 'subcounty_id' => 181],
            ['name' => 'Oloosirkon/Sholinke', 'subcounty_id' => 181],
            ['name' => 'Kaputiei North', 'subcounty_id' => 181],
            ['name' => 'Kenya Marble Quarry', 'subcounty_id' => 181],

            // Kajiado West Subcounty (Subcounty ID: 182)
            ['name' => 'Ewuaso Oonkidong\'i', 'subcounty_id' => 182],
            ['name' => 'Keekonyokie', 'subcounty_id' => 182],
            ['name' => 'Magadi', 'subcounty_id' => 182],
            ['name' => 'Olkeri', 'subcounty_id' => 182],

            // Kajiado South Subcounty (Subcounty ID: 183)
            ['name' => 'Loitokitok', 'subcounty_id' => 183],
            ['name' => 'Kimana', 'subcounty_id' => 183],
            ['name' => 'Entonet/Lenkism', 'subcounty_id' => 183],
            ['name' => 'Rombo', 'subcounty_id' => 183],

            // Kericho County (County ID: 35)

            // Belgut Subcounty (Subcounty ID: 184)
            ['name' => 'Waldai', 'subcounty_id' => 184],
            ['name' => 'Kapsoit', 'subcounty_id' => 184],
            ['name' => 'Kabianga', 'subcounty_id' => 184],
            ['name' => 'Cheptororiet/Seretut', 'subcounty_id' => 184],

            // Bureti Subcounty (Subcounty ID: 185)
            ['name' => 'Litein', 'subcounty_id' => 185],
            ['name' => 'Kisiara', 'subcounty_id' => 185],
            ['name' => 'Chemosot', 'subcounty_id' => 185],
            ['name' => 'Kapkatet', 'subcounty_id' => 185],

            // Ainamoi Subcounty (Subcounty ID: 186)
            ['name' => 'Kipchebor', 'subcounty_id' => 186],
            ['name' => 'Kapsaos', 'subcounty_id' => 186],
            ['name' => 'Kapkugerwet', 'subcounty_id' => 186],
            ['name' => 'Kapcheptororiet', 'subcounty_id' => 186],

            // Kipkelion East Subcounty (Subcounty ID: 187)
            ['name' => 'Chilchila', 'subcounty_id' => 187],
            ['name' => 'Chepseon', 'subcounty_id' => 187],
            ['name' => 'Londiani', 'subcounty_id' => 187],
            ['name' => 'Kedowa/Kimugul', 'subcounty_id' => 187],

            // Kipkelion West Subcounty (Subcounty ID: 188)
            ['name' => 'Kamasian', 'subcounty_id' => 188],
            ['name' => 'Sorget', 'subcounty_id' => 188],
            ['name' => 'Kunyak', 'subcounty_id' => 188],
            ['name' => 'Kamwingi', 'subcounty_id' => 188],

            // Soin/Sigowet Subcounty (Subcounty ID: 189)
            ['name' => 'Kaplelartet', 'subcounty_id' => 189],
            ['name' => 'Sigowet', 'subcounty_id' => 189],
            ['name' => 'Kapsorok', 'subcounty_id' => 189],
            ['name' => 'Cheplanget', 'subcounty_id' => 189],

            // Bomet County (County ID: 36)

            // Sotik Subcounty (Subcounty ID: 190)
            ['name' => 'Ndanai/Abosi', 'subcounty_id' => 190],
            ['name' => 'Rongena/Manaret', 'subcounty_id' => 190],
            ['name' => 'Kapletundo', 'subcounty_id' => 190],
            ['name' => 'Chemagel', 'subcounty_id' => 190],

            // Chepalungu Subcounty (Subcounty ID: 191)
            ['name' => 'Sigor', 'subcounty_id' => 191],
            ['name' => 'Chebunyo', 'subcounty_id' => 191],
            ['name' => 'Nyangores', 'subcounty_id' => 191],
            ['name' => 'Kong\'asis', 'subcounty_id' => 191],

            // Bomet East Subcounty (Subcounty ID: 192)
            ['name' => 'Chesoen', 'subcounty_id' => 192],
            ['name' => 'Kembu', 'subcounty_id' => 192],
            ['name' => 'Merigi', 'subcounty_id' => 192],
            ['name' => 'Longisa', 'subcounty_id' => 192],

            // Bomet Central Subcounty (Subcounty ID: 193)
            ['name' => 'Mutarakwa', 'subcounty_id' => 193],
            ['name' => 'Singorwet', 'subcounty_id' => 193],
            ['name' => 'Ndarawetta', 'subcounty_id' => 193],
            ['name' => 'Itembe', 'subcounty_id' => 193],

            // Konoin Subcounty (Subcounty ID: 194)
            ['name' => 'Boito', 'subcounty_id' => 194],
            ['name' => 'Embomos', 'subcounty_id' => 194],
            ['name' => 'Kimulot', 'subcounty_id' => 194],
            ['name' => 'Mogogosiek', 'subcounty_id' => 194],

            // Kakamega County (County ID: 37)

            // Lugari Subcounty (Subcounty ID: 195)
            ['name' => 'Chekalini', 'subcounty_id' => 195],
            ['name' => 'Chevaywa', 'subcounty_id' => 195],
            ['name' => 'Lugari', 'subcounty_id' => 195],
            ['name' => 'Lumakanda', 'subcounty_id' => 195],

            // Likuyani Subcounty (Subcounty ID: 196)
            ['name' => 'Likuyani', 'subcounty_id' => 196],
            ['name' => 'Kongoni', 'subcounty_id' => 196],
            ['name' => 'Sango', 'subcounty_id' => 196],
            ['name' => 'Nzoia', 'subcounty_id' => 196],

            // Malava Subcounty (Subcounty ID: 197)
            ['name' => 'West Kabras', 'subcounty_id' => 197],
            ['name' => 'Chevoso', 'subcounty_id' => 197],
            ['name' => 'Butali/Chegulo', 'subcounty_id' => 197],
            ['name' => 'Manda-Shivanga', 'subcounty_id' => 197],

            // Lurambi Subcounty (Subcounty ID: 198)
            ['name' => 'Butsotso East', 'subcounty_id' => 198],
            ['name' => 'Butsotso South', 'subcounty_id' => 198],
            ['name' => 'Butsotso Central', 'subcounty_id' => 198],
            ['name' => 'Shirere', 'subcounty_id' => 198],

            // Navakholo Subcounty (Subcounty ID: 199)
            ['name' => 'Ingostse-Mathia', 'subcounty_id' => 199],
            ['name' => 'Bunyala West', 'subcounty_id' => 199],
            ['name' => 'Bunyala Central', 'subcounty_id' => 199],
            ['name' => 'Bunyala East', 'subcounty_id' => 199],

            // Mumias West Subcounty (Subcounty ID: 200)
            ['name' => 'Mumias Central', 'subcounty_id' => 200],
            ['name' => 'Mumias North', 'subcounty_id' => 200],
            ['name' => 'Etenje', 'subcounty_id' => 200],
            ['name' => 'Musanda', 'subcounty_id' => 200],

            // Mumias East Subcounty (Subcounty ID: 201)
            ['name' => 'Lusheya/Lubinu', 'subcounty_id' => 201],
            ['name' => 'Malaha/Isongo/Makunga', 'subcounty_id' => 201],
            ['name' => 'East Wanga', 'subcounty_id' => 201],
            ['name' => 'Khalaba', 'subcounty_id' => 201],

            // Matungu Subcounty (Subcounty ID: 202)
            ['name' => 'Kholera', 'subcounty_id' => 202],
            ['name' => 'Khalaba', 'subcounty_id' => 202],
            ['name' => 'Koyonzo', 'subcounty_id' => 202],
            ['name' => 'Mayoni', 'subcounty_id' => 202],

            // Butere Subcounty (Subcounty ID: 203)
            ['name' => 'Marama Central', 'subcounty_id' => 203],
            ['name' => 'Marama North', 'subcounty_id' => 203],
            ['name' => 'Marama West', 'subcounty_id' => 203],
            ['name' => 'Marama South', 'subcounty_id' => 203],

            // Khwisero Subcounty (Subcounty ID: 204)
            ['name' => 'Kisa North', 'subcounty_id' => 204],
            ['name' => 'Kisa Central', 'subcounty_id' => 204],
            ['name' => 'Kisa East', 'subcounty_id' => 204],
            ['name' => 'Kisa West', 'subcounty_id' => 204],

            // Shinyalu Subcounty (Subcounty ID: 205)
            ['name' => 'Murhanda', 'subcounty_id' => 205],
            ['name' => 'Isukha Central', 'subcounty_id' => 205],
            ['name' => 'Isukha East', 'subcounty_id' => 205],
            ['name' => 'Isukha South', 'subcounty_id' => 205],

            // Ikolomani Subcounty (Subcounty ID: 206)
            ['name' => 'Idakho North', 'subcounty_id' => 206],
            ['name' => 'Idakho Central', 'subcounty_id' => 206],
            ['name' => 'Idakho South', 'subcounty_id' => 206],
            ['name' => 'Idakho East', 'subcounty_id' => 206],

            // Vihiga County (County ID: 38)

            // Vihiga Subcounty (Subcounty ID: 207)
            ['name' => 'Lugaga/Wamuluma', 'subcounty_id' => 207],
            ['name' => 'Central Maragoli', 'subcounty_id' => 207],
            ['name' => 'West Sabatia', 'subcounty_id' => 207],
            ['name' => 'Wodanga', 'subcounty_id' => 207],

            // Sabatia Subcounty (Subcounty ID: 208)
            ['name' => 'Lyaduywa/Izava', 'subcounty_id' => 208],
            ['name' => 'Busali', 'subcounty_id' => 208],
            ['name' => 'Chavakali', 'subcounty_id' => 208],
            ['name' => 'North Maragoli', 'subcounty_id' => 208],

            // Hamisi Subcounty (Subcounty ID: 209)
            ['name' => 'Muhudu', 'subcounty_id' => 209],
            ['name' => 'Tigoi', 'subcounty_id' => 209],
            ['name' => 'Shiru', 'subcounty_id' => 209],
            ['name' => 'Shamakhokho', 'subcounty_id' => 209],

            // Luanda Subcounty (Subcounty ID: 210)
            ['name' => 'Luanda South', 'subcounty_id' => 210],
            ['name' => 'Luanda Township', 'subcounty_id' => 210],
            ['name' => 'Mwibona', 'subcounty_id' => 210],
            ['name' => 'Emabungo', 'subcounty_id' => 210],

            // Emuhaya Subcounty (Subcounty ID: 211)
            ['name' => 'West Bunyore', 'subcounty_id' => 211],
            ['name' => 'Central Bunyore', 'subcounty_id' => 211],
            ['name' => 'East Bunyore', 'subcounty_id' => 211],
            ['name' => 'North Bunyore', 'subcounty_id' => 211],

            // Bungoma County (County ID: 39)

            // Bumula Subcounty (Subcounty ID: 212)
            ['name' => 'South Bukusu', 'subcounty_id' => 212],
            ['name' => 'Kanduyi', 'subcounty_id' => 212],
            ['name' => 'West Bukusu', 'subcounty_id' => 212],
            ['name' => 'Siboti', 'subcounty_id' => 212],

            // Kanduyi Subcounty (Subcounty ID: 213)
            ['name' => 'Bukembe East', 'subcounty_id' => 213],
            ['name' => 'Bukembe West', 'subcounty_id' => 213],
            ['name' => 'Kabula', 'subcounty_id' => 213],
            ['name' => 'Tuuti/Marakaru', 'subcounty_id' => 213],

            // Webuye East Subcounty (Subcounty ID: 214)
            ['name' => 'Matisi', 'subcounty_id' => 214],
            ['name' => 'Sitikho', 'subcounty_id' => 214],
            ['name' => 'Ndivisi', 'subcounty_id' => 214],
            ['name' => 'Mihuu', 'subcounty_id' => 214],

            // Webuye West Subcounty (Subcounty ID: 215)
            ['name' => 'Misikhu', 'subcounty_id' => 215],
            ['name' => 'Khalumuli', 'subcounty_id' => 215],
            ['name' => 'Makutano', 'subcounty_id' => 215],
            ['name' => 'Namwela', 'subcounty_id' => 215],

            // Kimilili Subcounty (Subcounty ID: 216)
            ['name' => 'Kibingei', 'subcounty_id' => 216],
            ['name' => 'Maeni', 'subcounty_id' => 216],
            ['name' => 'Kimilili', 'subcounty_id' => 216],
            ['name' => 'Kibomet', 'subcounty_id' => 216],

            // Tongaren Subcounty (Subcounty ID: 217)
            ['name' => 'Milima', 'subcounty_id' => 217],
            ['name' => 'Naitiri/Kabuyefwe', 'subcounty_id' => 217],
            ['name' => 'Mbakalo', 'subcounty_id' => 217],
            ['name' => 'Soysambu/Mitua', 'subcounty_id' => 217],

            // Kabuchai Subcounty (Subcounty ID: 218)
            ['name' => 'Bwake/Luuya', 'subcounty_id' => 218],
            ['name' => 'Mukuyuni', 'subcounty_id' => 218],
            ['name' => 'Chwele/Kabuchai', 'subcounty_id' => 218],
            ['name' => 'Luuya/Khalumuli', 'subcounty_id' => 218],

            // Mt. Elgon Subcounty (Subcounty ID: 219)
            ['name' => 'Cheptais', 'subcounty_id' => 219],
            ['name' => 'Kopsiro', 'subcounty_id' => 219],
            ['name' => 'Chesikaki', 'subcounty_id' => 219],
            ['name' => 'Kaptama', 'subcounty_id' => 219],

            // Sirisia Subcounty (Subcounty ID: 220)
            ['name' => 'Malakisi/South Kulisiru', 'subcounty_id' => 220],
            ['name' => 'Namwela', 'subcounty_id' => 220],
            ['name' => 'Lwandanyi', 'subcounty_id' => 220],
            ['name' => 'Chongeywo', 'subcounty_id' => 220],

            // Busia County (County ID: 40)

            // Teso North Subcounty (Subcounty ID: 221)
            ['name' => 'Ang\'urai South', 'subcounty_id' => 221],
            ['name' => 'Ang\'urai North', 'subcounty_id' => 221],
            ['name' => 'Ang\'urai East', 'subcounty_id' => 221],
            ['name' => 'Moding', 'subcounty_id' => 221],

            // Teso South Subcounty (Subcounty ID: 222)
            ['name' => 'Amukura West', 'subcounty_id' => 222],
            ['name' => 'Amukura East', 'subcounty_id' => 222],
            ['name' => 'Amukura Central', 'subcounty_id' => 222],
            ['name' => 'Ang\'urai East', 'subcounty_id' => 222],

            // Nambale Subcounty (Subcounty ID: 223)
            ['name' => 'Bukhayo North', 'subcounty_id' => 223],
            ['name' => 'Bukhayo Central', 'subcounty_id' => 223],
            ['name' => 'Bukhayo East', 'subcounty_id' => 223],
            ['name' => 'Bukhayo West', 'subcounty_id' => 223],

            // Matayos Subcounty (Subcounty ID: 224)
            ['name' => 'Busibwabo', 'subcounty_id' => 224],
            ['name' => 'Bukhayo North/Walatsi', 'subcounty_id' => 224],
            ['name' => 'Bukhayo Central', 'subcounty_id' => 224],
            ['name' => 'Matayos South', 'subcounty_id' => 224],

            // Butula Subcounty (Subcounty ID: 225)
            ['name' => 'Marachi North', 'subcounty_id' => 225],
            ['name' => 'Marachi Central', 'subcounty_id' => 225],
            ['name' => 'Marachi West', 'subcounty_id' => 225],
            ['name' => 'Elugulu', 'subcounty_id' => 225],

            // Funyula Subcounty (Subcounty ID: 226)
            ['name' => 'Bwiri', 'subcounty_id' => 226],
            ['name' => 'Namboboto Nambuku', 'subcounty_id' => 226],
            ['name' => 'Ageng\'a Nanguba', 'subcounty_id' => 226],
            ['name' => 'Odiado', 'subcounty_id' => 226],

            // Budalangi Subcounty (Subcounty ID: 227)
            ['name' => 'Bunyala Central', 'subcounty_id' => 227],
            ['name' => 'Bunyala North', 'subcounty_id' => 227],
            ['name' => 'Bunyala South', 'subcounty_id' => 227],
            ['name' => 'Bunyala West', 'subcounty_id' => 227],

            // Siaya County (County ID: 41)

            // Ugenya Subcounty (Subcounty ID: 228)
            ['name' => 'East Ugenya', 'subcounty_id' => 228],
            ['name' => 'West Ugenya', 'subcounty_id' => 228],
            ['name' => 'North Ugenya', 'subcounty_id' => 228],
            ['name' => 'South Ugenya', 'subcounty_id' => 228],

            // Ugunja Subcounty (Subcounty ID: 229)
            ['name' => 'Ugunja', 'subcounty_id' => 229],
            ['name' => 'Sigomere', 'subcounty_id' => 229],
            ['name' => 'Sidindi', 'subcounty_id' => 229],
            ['name' => 'North Ugenya', 'subcounty_id' => 229],

            // Alego Usonga Subcounty (Subcounty ID: 230)
            ['name' => 'West Alego', 'subcounty_id' => 230],
            ['name' => 'Central Alego', 'subcounty_id' => 230],
            ['name' => 'North Alego', 'subcounty_id' => 230],
            ['name' => 'South Alego', 'subcounty_id' => 230],

            // Gem Subcounty (Subcounty ID: 231)
            ['name' => 'Yala Township', 'subcounty_id' => 231],
            ['name' => 'South Gem', 'subcounty_id' => 231],
            ['name' => 'Central Gem', 'subcounty_id' => 231],
            ['name' => 'West Gem', 'subcounty_id' => 231],

            // Bondo Subcounty (Subcounty ID: 232)
            ['name' => 'West Yimbo', 'subcounty_id' => 232],
            ['name' => 'Central Sakwa', 'subcounty_id' => 232],
            ['name' => 'West Sakwa', 'subcounty_id' => 232],
            ['name' => 'North Sakwa', 'subcounty_id' => 232],

            // Rarieda Subcounty (Subcounty ID: 233)
            ['name' => 'West Asembo', 'subcounty_id' => 233],
            ['name' => 'East Asembo', 'subcounty_id' => 233],
            ['name' => 'North Asembo', 'subcounty_id' => 233],
            ['name' => 'South Asembo', 'subcounty_id' => 233],

            // Kisumu County (County ID: 42)

            // Kisumu East Subcounty (Subcounty ID: 234)
            ['name' => 'Nyalenda A', 'subcounty_id' => 234],
            ['name' => 'Nyalenda B', 'subcounty_id' => 234],
            ['name' => 'Kolwa Central', 'subcounty_id' => 234],
            ['name' => 'Manyatta B', 'subcounty_id' => 234],

            // Kisumu West Subcounty (Subcounty ID: 235)
            ['name' => 'Kisumu North', 'subcounty_id' => 235],
            ['name' => 'West Kisumu', 'subcounty_id' => 235],
            ['name' => 'North West Kisumu', 'subcounty_id' => 235],
            ['name' => 'South West Kisumu', 'subcounty_id' => 235],

            // Kisumu Central Subcounty (Subcounty ID: 236)
            ['name' => 'Railways', 'subcounty_id' => 236],
            ['name' => 'Manyatta A', 'subcounty_id' => 236],
            ['name' => 'Shauri Moyo Kaloleni', 'subcounty_id' => 236],
            ['name' => 'Nyalenda', 'subcounty_id' => 236],

            // Nyando Subcounty (Subcounty ID: 237)
            ['name' => 'Awasi', 'subcounty_id' => 237],
            ['name' => 'Kabonyo', 'subcounty_id' => 237],
            ['name' => 'Ahero', 'subcounty_id' => 237],
            ['name' => 'Nyando', 'subcounty_id' => 237],

            // Muhoroni Subcounty (Subcounty ID: 238)
            ['name' => 'Muhoroni/Koru', 'subcounty_id' => 238],
            ['name' => 'Ombeyi', 'subcounty_id' => 238],
            ['name' => 'Masogo/Nyang\'oma', 'subcounty_id' => 238],
            ['name' => 'Koru', 'subcounty_id' => 238],

            // Nyakach Subcounty (Subcounty ID: 239)
            ['name' => 'Central Nyakach', 'subcounty_id' => 239],
            ['name' => 'North Nyakach', 'subcounty_id' => 239],
            ['name' => 'South Nyakach', 'subcounty_id' => 239],
            ['name' => 'West Nyakach', 'subcounty_id' => 239],

            // Seme Subcounty (Subcounty ID: 240)
            ['name' => 'West Seme', 'subcounty_id' => 240],
            ['name' => 'East Seme', 'subcounty_id' => 240],
            ['name' => 'North Seme', 'subcounty_id' => 240],
            ['name' => 'South Seme', 'subcounty_id' => 240],

            // Homa Bay County (County ID: 43)

            // Rangwe Subcounty (Subcounty ID: 241)
            ['name' => 'West Gem', 'subcounty_id' => 241],
            ['name' => 'East Gem', 'subcounty_id' => 241],
            ['name' => 'Central Gem', 'subcounty_id' => 241],
            ['name' => 'South Gem', 'subcounty_id' => 241],

            // Homa Bay Town Subcounty (Subcounty ID: 242)
            ['name' => 'Homa Bay Central', 'subcounty_id' => 242],
            ['name' => 'Homa Bay East', 'subcounty_id' => 242],
            ['name' => 'Homa Bay North', 'subcounty_id' => 242],
            ['name' => 'Homa Bay South', 'subcounty_id' => 242],

            // Ndhiwa Subcounty (Subcounty ID: 243)
            ['name' => 'Kanyamwa Kosewe', 'subcounty_id' => 243],
            ['name' => 'Kanyikela', 'subcounty_id' => 243],
            ['name' => 'Kanyadoto', 'subcounty_id' => 243],
            ['name' => 'Kabouch North', 'subcounty_id' => 243],

            // Mbita Subcounty (Subcounty ID: 244)
            ['name' => 'Lambwe', 'subcounty_id' => 244],
            ['name' => 'Gembe', 'subcounty_id' => 244],
            ['name' => 'Rusinga', 'subcounty_id' => 244],
            ['name' => 'Mfangano', 'subcounty_id' => 244],

            // Suba North Subcounty (Subcounty ID: 245)
            ['name' => 'Rusinga Island', 'subcounty_id' => 245],
            ['name' => 'Lambwe West', 'subcounty_id' => 245],
            ['name' => 'Kasgunga', 'subcounty_id' => 245],
            ['name' => 'Ruma Kanyamwa', 'subcounty_id' => 245],

            // Suba South Subcounty (Subcounty ID: 246)
            ['name' => 'Central Kasipul', 'subcounty_id' => 246],
            ['name' => 'East Kasipul', 'subcounty_id' => 246],
            ['name' => 'West Kasipul', 'subcounty_id' => 246],
            ['name' => 'South Kasipul', 'subcounty_id' => 246],

            // Karachuonyo Subcounty (Subcounty ID: 247)
            ['name' => 'Kanyaluo', 'subcounty_id' => 247],
            ['name' => 'Kendu Bay Town', 'subcounty_id' => 247],
            ['name' => 'Wang\'chieng', 'subcounty_id' => 247],
            ['name' => 'Central Karachuonyo', 'subcounty_id' => 247],

            // Kabondo Kasipul Subcounty (Subcounty ID: 248)
            ['name' => 'Kabondo West', 'subcounty_id' => 248],
            ['name' => 'Kabondo East', 'subcounty_id' => 248],
            ['name' => 'Kasewe', 'subcounty_id' => 248],
            ['name' => 'Kokwanyo', 'subcounty_id' => 248],

            // Migori County (County ID: 44)

            // Rongo Subcounty (Subcounty ID: 249)
            ['name' => 'North Kamagambo', 'subcounty_id' => 249],
            ['name' => 'South Kamagambo', 'subcounty_id' => 249],
            ['name' => 'Central Kamagambo', 'subcounty_id' => 249],
            ['name' => 'East Kamagambo', 'subcounty_id' => 249],

            // Awendo Subcounty (Subcounty ID: 250)
            ['name' => 'Suna West', 'subcounty_id' => 250],
            ['name' => 'Suna East', 'subcounty_id' => 250],
            ['name' => 'Wiga', 'subcounty_id' => 250],
            ['name' => 'North Kanyamkago', 'subcounty_id' => 250],

            // Suna East Subcounty (Subcounty ID: 251)
            ['name' => 'West Kanyamkago', 'subcounty_id' => 251],
            ['name' => 'North Sakwa', 'subcounty_id' => 251],
            ['name' => 'Central Sakwa', 'subcounty_id' => 251],
            ['name' => 'South Sakwa', 'subcounty_id' => 251],

            // Suna West Subcounty (Subcounty ID: 252)
            ['name' => 'South Sakwa', 'subcounty_id' => 252],
            ['name' => 'East Sakwa', 'subcounty_id' => 252],
            ['name' => 'Central Sakwa', 'subcounty_id' => 252],
            ['name' => 'West Sakwa', 'subcounty_id' => 252],

            // Uriri Subcounty (Subcounty ID: 253)
            ['name' => 'West Kanyamkago', 'subcounty_id' => 253],
            ['name' => 'Central Kanyamkago', 'subcounty_id' => 253],
            ['name' => 'South Kanyamkago', 'subcounty_id' => 253],
            ['name' => 'North Kanyamkago', 'subcounty_id' => 253],

            // Nyatike Subcounty (Subcounty ID: 254)
            ['name' => 'Kachieng', 'subcounty_id' => 254],
            ['name' => 'Central Nyatike', 'subcounty_id' => 254],
            ['name' => 'North Nyatike', 'subcounty_id' => 254],
            ['name' => 'South Nyatike', 'subcounty_id' => 254],

            // Kuria West Subcounty (Subcounty ID: 255)
            ['name' => 'Bukuria Central', 'subcounty_id' => 255],
            ['name' => 'Bukuria East', 'subcounty_id' => 255],
            ['name' => 'Bukuria North', 'subcounty_id' => 255],
            ['name' => 'Bukuria South', 'subcounty_id' => 255],

            // Kuria East Subcounty (Subcounty ID: 256)
            ['name' => 'Nyabasi East', 'subcounty_id' => 256],
            ['name' => 'Nyabasi West', 'subcounty_id' => 256],
            ['name' => 'Gokeharaka/Getambwega', 'subcounty_id' => 256],
            ['name' => 'Ntimaru East', 'subcounty_id' => 256],

            // Kisii County (County ID: 45)

            // Kitutu Chache North Subcounty (Subcounty ID: 257)
            ['name' => 'Kiamokama', 'subcounty_id' => 257],
            ['name' => 'Nyatieko', 'subcounty_id' => 257],
            ['name' => 'Bogeka', 'subcounty_id' => 257],
            ['name' => 'Gesusu', 'subcounty_id' => 257],

            // Kitutu Chache South Subcounty (Subcounty ID: 258)
            ['name' => 'Bogisero', 'subcounty_id' => 258],
            ['name' => 'Bogiakumu', 'subcounty_id' => 258],
            ['name' => 'Kiogoro', 'subcounty_id' => 258],
            ['name' => 'Nyamasibi', 'subcounty_id' => 258],

            // Nyaribari Masaba Subcounty (Subcounty ID: 259)
            ['name' => 'Kiamokama', 'subcounty_id' => 259],
            ['name' => 'Gesusu', 'subcounty_id' => 259],
            ['name' => 'Nyatieko', 'subcounty_id' => 259],
            ['name' => 'Masimba', 'subcounty_id' => 259],

            // Nyaribari Chache Subcounty (Subcounty ID: 260)
            ['name' => 'Kisii Central', 'subcounty_id' => 260],
            ['name' => 'Bonyamatuta', 'subcounty_id' => 260],
            ['name' => 'Ibeno', 'subcounty_id' => 260],
            ['name' => 'Nyamasibi', 'subcounty_id' => 260],

            // Bonchari Subcounty (Subcounty ID: 261)
            ['name' => 'Riana', 'subcounty_id' => 261],
            ['name' => 'Bomorenda', 'subcounty_id' => 261],
            ['name' => 'Nyakoe', 'subcounty_id' => 261],
            ['name' => 'Bogiakumu', 'subcounty_id' => 261],

            // South Mugirango Subcounty (Subcounty ID: 262)
            ['name' => 'Tabaka', 'subcounty_id' => 262],
            ['name' => 'Boikanga', 'subcounty_id' => 262],
            ['name' => 'Getenga', 'subcounty_id' => 262],
            ['name' => 'Moticho', 'subcounty_id' => 262],

            // Bomachoge Borabu Subcounty (Subcounty ID: 263)
            ['name' => 'Bokimonge', 'subcounty_id' => 263],
            ['name' => 'Magenche', 'subcounty_id' => 263],
            ['name' => 'Boitangare', 'subcounty_id' => 263],
            ['name' => 'Nyamache', 'subcounty_id' => 263],

            // Bomachoge Chache Subcounty (Subcounty ID: 264)
            ['name' => 'Boikanga', 'subcounty_id' => 264],
            ['name' => 'Boochi Tendere', 'subcounty_id' => 264],
            ['name' => 'Tabaka', 'subcounty_id' => 264],
            ['name' => 'Nyamarambe', 'subcounty_id' => 264],

            // Nyamira County (County ID: 46)

            // West Mugirango Subcounty (Subcounty ID: 265)
            ['name' => 'Bosamaro', 'subcounty_id' => 265],
            ['name' => 'Itibo', 'subcounty_id' => 265],
            ['name' => 'Bogichora', 'subcounty_id' => 265],
            ['name' => 'Nyamaiya', 'subcounty_id' => 265],

            // North Mugirango Subcounty (Subcounty ID: 266)
            ['name' => 'Ekerenyo', 'subcounty_id' => 266],
            ['name' => 'Magwagwa', 'subcounty_id' => 266],
            ['name' => 'Nyansiongo', 'subcounty_id' => 266],
            ['name' => 'Bokeira', 'subcounty_id' => 266],

            // Borabu Subcounty (Subcounty ID: 267)
            ['name' => 'Esise', 'subcounty_id' => 267],
            ['name' => 'Mekenene', 'subcounty_id' => 267],
            ['name' => 'Kijauri', 'subcounty_id' => 267],
            ['name' => 'Rigoma', 'subcounty_id' => 267],

            // Manga Subcounty (Subcounty ID: 268)
            ['name' => 'Manga', 'subcounty_id' => 268],
            ['name' => 'Nyamira Town', 'subcounty_id' => 268],
            ['name' => 'Itibo', 'subcounty_id' => 268],
            ['name' => 'Bosamaro', 'subcounty_id' => 268],

            // Nairobi County (County ID: 47)

            // Westlands Subcounty (Subcounty ID: 269)
            ['name' => 'Kangemi', 'subcounty_id' => 269],
            ['name' => 'Mountain View', 'subcounty_id' => 269],
            ['name' => 'Westlands', 'subcounty_id' => 269],
            ['name' => 'Parklands/Highridge', 'subcounty_id' => 269],

            // Dagoretti North Subcounty (Subcounty ID: 270)
            ['name' => 'Kilimani', 'subcounty_id' => 270],
            ['name' => 'Kawangware', 'subcounty_id' => 270],
            ['name' => 'Gatina', 'subcounty_id' => 270],
            ['name' => 'Kileleshwa', 'subcounty_id' => 270],

            // Dagoretti South Subcounty (Subcounty ID: 271)
            ['name' => 'Mutuini', 'subcounty_id' => 271],
            ['name' => 'Riruta', 'subcounty_id' => 271],
            ['name' => 'Uthiru/Ruthimitu', 'subcounty_id' => 271],
            ['name' => 'Waithaka', 'subcounty_id' => 271],

            // Langata Subcounty (Subcounty ID: 272)
            ['name' => 'Karen', 'subcounty_id' => 272],
            ['name' => 'Nairobi West', 'subcounty_id' => 272],
            ['name' => 'Mugumu-Ini', 'subcounty_id' => 272],
            ['name' => 'South C', 'subcounty_id' => 272],

            // Kibra Subcounty (Subcounty ID: 273)
            ['name' => 'Lindi', 'subcounty_id' => 273],
            ['name' => 'Makina', 'subcounty_id' => 273],
            ['name' => 'Woodley/Kenyatta Golf Course', 'subcounty_id' => 273],
            ['name' => 'Sarang\'ombe', 'subcounty_id' => 273],

            // Roysambu Subcounty (Subcounty ID: 274)
            ['name' => 'Roysambu', 'subcounty_id' => 274],
            ['name' => 'Zimmerman', 'subcounty_id' => 274],
            ['name' => 'Githurai', 'subcounty_id' => 274],
            ['name' => 'Kahawa', 'subcounty_id' => 274],

            // Kasarani Subcounty (Subcounty ID: 275)
            ['name' => 'Mwiki', 'subcounty_id' => 275],
            ['name' => 'Kasarani', 'subcounty_id' => 275],
            ['name' => 'Clay City', 'subcounty_id' => 275],
            ['name' => 'Njiru', 'subcounty_id' => 275],

            // Ruaraka Subcounty (Subcounty ID: 276)
            ['name' => 'Babadogo', 'subcounty_id' => 276],
            ['name' => 'Lucky Summer', 'subcounty_id' => 276],
            ['name' => 'Korogocho', 'subcounty_id' => 276],
            ['name' => 'Mathare North', 'subcounty_id' => 276],

            // Embakasi South Subcounty (Subcounty ID: 277)
            ['name' => 'Imara Daima', 'subcounty_id' => 277],
            ['name' => 'Kwa Njenga', 'subcounty_id' => 277],
            ['name' => 'Kwa Reuben', 'subcounty_id' => 277],
            ['name' => 'Pipeline', 'subcounty_id' => 277],

            // Embakasi North Subcounty (Subcounty ID: 278)
            ['name' => 'Kariobangi North', 'subcounty_id' => 278],
            ['name' => 'Dandora I', 'subcounty_id' => 278],
            ['name' => 'Dandora II', 'subcounty_id' => 278],
            ['name' => 'Dandora III', 'subcounty_id' => 278],

            // Embakasi Central Subcounty (Subcounty ID: 279)
            ['name' => 'Kayole North', 'subcounty_id' => 279],
            ['name' => 'Kayole South', 'subcounty_id' => 279],
            ['name' => 'Komarock', 'subcounty_id' => 279],
            ['name' => 'Matopeni/Spring Valley', 'subcounty_id' => 279],

            // Embakasi East Subcounty (Subcounty ID: 280)
            ['name' => 'Utawala', 'subcounty_id' => 280],
            ['name' => 'Mihango', 'subcounty_id' => 280],
            ['name' => 'Tasia', 'subcounty_id' => 280],
            ['name' => 'Donholm', 'subcounty_id' => 280],

            // Embakasi West Subcounty (Subcounty ID: 281)
            ['name' => 'Umoja I', 'subcounty_id' => 281],
            ['name' => 'Umoja II', 'subcounty_id' => 281],
            ['name' => 'Mowlem', 'subcounty_id' => 281],
            ['name' => 'Kariobangi South', 'subcounty_id' => 281],

            // Makadara Subcounty (Subcounty ID: 282)
            ['name' => 'Viwandani', 'subcounty_id' => 282],
            ['name' => 'Makongeni', 'subcounty_id' => 282],
            ['name' => 'Harambee', 'subcounty_id' => 282],
            ['name' => 'Maringo/Hamza', 'subcounty_id' => 282],

            // Kamukunji Subcounty (Subcounty ID: 283)
            ['name' => 'Pumwani', 'subcounty_id' => 283],
            ['name' => 'Eastleigh North', 'subcounty_id' => 283],
            ['name' => 'Eastleigh South', 'subcounty_id' => 283],
            ['name' => 'Airbase', 'subcounty_id' => 283],

            // Starehe Subcounty (Subcounty ID: 284)
            ['name' => 'Nairobi Central', 'subcounty_id' => 284],
            ['name' => 'Ngara', 'subcounty_id' => 284],
            ['name' => 'Pangani', 'subcounty_id' => 284],
            ['name' => 'Ziwani/Kariokor', 'subcounty_id' => 284],

            // Mathare Subcounty (Subcounty ID: 285)
            ['name' => 'Hospital', 'subcounty_id' => 285],
            ['name' => 'Mabatini', 'subcounty_id' => 285],
            ['name' => 'Huruma', 'subcounty_id' => 285],
            ['name' => 'Kiamaiko', 'subcounty_id' => 285],
        ];

        DB::table('wards')->insert($wards);
    }
}
