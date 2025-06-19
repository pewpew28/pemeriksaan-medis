<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\ServiceItem;

class ServiceDataSeeder extends Seeder
{
    public function run(): void
    {
        // Data kategori dan item berdasarkan price list
        $categoriesData = [
            [
                'name' => 'Pemeriksaan Darah Lengkap',
                'code' => 'HEMA',
                'description' => 'Pemeriksaan hematologi lengkap',
                'sort_order' => 1,
                'items' => [
                    ['name' => 'Automatic', 'price' => 120000],
                    ['name' => 'Hitung Jenis', 'price' => 60000],
                    ['name' => 'Morfologi Sel', 'price' => 170000],
                    ['name' => 'Retikulosit', 'price' => 235000],
                ]
            ],
            [
                'name' => 'Profile Hb Abnormal',
                'code' => 'HB',
                'description' => 'Pemeriksaan hemoglobin abnormal',
                'sort_order' => 2,
                'items' => [
                    ['name' => 'Elektroforesis', 'price' => 1150000],
                ]
            ],
            [
                'name' => 'Golongan Darah',
                'code' => 'ABO',
                'description' => 'Pemeriksaan golongan darah',
                'sort_order' => 3,
                'items' => [
                    ['name' => 'Golongan Darah A,B,O', 'price' => 25000],
                    ['name' => 'Golongan Darah & Rhesus', 'price' => 25000],
                    ['name' => 'Serum Fe & TIBC', 'price' => 320000],
                    ['name' => 'Ferritin', 'price' => 350000],
                ]
            ],
            [
                'name' => 'Test Koagulasi',
                'code' => 'KOAG',
                'description' => 'Pemeriksaan koagulasi darah',
                'sort_order' => 4,
                'items' => [
                    ['name' => 'Masa Perdarahan', 'price' => 40000],
                    ['name' => 'Masa Pembekuan', 'price' => 40000],
                ]
            ],
            [
                'name' => 'Urine Analisa',
                'code' => 'URIN',
                'description' => 'Pemeriksaan urine lengkap',
                'sort_order' => 5,
                'items' => [
                    ['name' => 'Urine lengkap', 'price' => 80000],
                    ['name' => 'Test Kehamilan', 'price' => 35000],
                    ['name' => 'Micro Albumin', 'price' => 350000],
                ]
            ],
            [
                'name' => 'Parasit Darah',
                'code' => 'PARA',
                'description' => 'Pemeriksaan parasit dalam darah',
                'sort_order' => 6,
                'items' => [
                    ['name' => 'Parasit Malaria', 'price' => 150000],
                    ['name' => 'Parasit Filaria', 'price' => 150000],
                    ['name' => 'Rapid Test Malaria', 'price' => 200000],
                ]
            ],
            [
                'name' => 'Test Gula Darah',
                'code' => 'GULA',
                'description' => 'Pemeriksaan kadar gula darah',
                'sort_order' => 7,
                'items' => [
                    ['name' => 'Gula darah puasa', 'price' => 30000],
                    ['name' => 'Gula Darah 2 jam p.p', 'price' => 30000],
                    ['name' => 'HbA1C', 'price' => 250000],
                ]
            ],
            [
                'name' => 'Pemeriksaan Elektrolit',
                'code' => 'ELEK',
                'description' => 'Pemeriksaan elektrolit darah',
                'sort_order' => 8,
                'items' => [
                    ['name' => 'Natrium (+)', 'price' => 85000],
                    ['name' => 'Kalium (+)', 'price' => 250000, 'code' => 'K+'],
                    ['name' => 'Chlorida (-)', 'price' => 85000],
                    ['name' => 'Magnesium', 'price' => 150000],
                    ['name' => 'Phosphor', 'price' => 220000],
                    ['name' => 'Calcium', 'price' => 150000],
                    ['name' => 'Calcium Ion', 'price' => 350000],
                ]
            ],
            [
                'name' => 'Fungsi Ginjal',
                'code' => 'GINJAL',
                'description' => 'Pemeriksaan fungsi ginjal',
                'sort_order' => 9,
                'items' => [
                    ['name' => 'Ureum', 'price' => 75000],
                    ['name' => 'Kreatinine', 'price' => 75000],
                    ['name' => 'Uric Acid', 'price' => 75000],
                    ['name' => 'Urea Clearance', 'price' => 180000],
                    ['name' => 'Kreatinine Clearance', 'price' => 180000],
                    ['name' => 'Urine lengkap', 'price' => 80000],
                ]
            ],
            [
                'name' => 'Profile Pancreas',
                'code' => 'PANC',
                'description' => 'Pemeriksaan fungsi pankreas',
                'sort_order' => 10,
                'items' => [
                    ['name' => 'Amylase', 'price' => 190000],
                    ['name' => 'Lypase', 'price' => 190000],
                ]
            ],
            [
                'name' => 'Fungsi Hati',
                'code' => 'HATI',
                'description' => 'Pemeriksaan fungsi hati',
                'sort_order' => 11,
                'items' => [
                    ['name' => 'Bilirubin Total/Direct', 'price' => 140000],
                    ['name' => 'S.G.O.T', 'price' => 70000],
                    ['name' => 'S.G.P.T', 'price' => 400000],
                    ['name' => 'Alkali Posptase', 'price' => 70000],
                    ['name' => 'Gamma GT', 'price' => 70000],
                    ['name' => 'Total Protein', 'price' => 70000],
                    ['name' => 'Albumin', 'price' => 70000],
                    ['name' => 'Globulin', 'price' => 70000],
                    ['name' => 'S.P.E', 'price' => 620000],
                    ['name' => 'Cholinesterase', 'price' => 200000],
                    ['name' => 'HBs Antigen', 'price' => 140000],
                    ['name' => 'Anti HBs', 'price' => 140000],
                    ['name' => 'Anti HCV', 'price' => 300000],
                    ['name' => 'HBs Ag (Titer)', 'price' => 270000],
                    ['name' => 'Anti HBs (Titer)', 'price' => 240000],
                    ['name' => 'Anti Hbc (Total)', 'price' => 650000],
                    ['name' => 'Ig G Anti HBc', 'price' => 320000],
                    ['name' => 'Ig M Anti HAV', 'price' => 470000],
                    ['name' => 'HBe Antigen', 'price' => 320000],
                    ['name' => 'Anti HBe', 'price' => 580000],
                    ['name' => 'Anti HAV (Total)', 'price' => 950000],
                ]
            ],
            [
                'name' => 'Lipid Profile',
                'code' => 'LIPID',
                'description' => 'Pemeriksaan profil lemak darah',
                'sort_order' => 12,
                'items' => [
                    ['name' => 'Total Cholesterol', 'price' => 80000],
                    ['name' => 'HDL Cholesterol', 'price' => 80000],
                    ['name' => 'LDL Cholesterol', 'price' => 80000],
                    ['name' => 'Triglycerida', 'price' => 80000],
                    ['name' => 'Total Lipid', 'price' => 100000],
                    ['name' => 'Apo A1', 'price' => 700000],
                    ['name' => 'Apo B', 'price' => 450000],
                ]
            ],
            [
                'name' => 'Fungsi Jantung',
                'code' => 'JANTUNG',
                'description' => 'Pemeriksaan fungsi jantung',
                'sort_order' => 13,
                'items' => [
                    ['name' => 'C.P.K./CK/CK Nac', 'price' => 270000],
                ]
            ],
            [
                'name' => 'Hormone & Endokrin',
                'code' => 'HORM',
                'description' => 'Pemeriksaan hormon dan endokrin',
                'sort_order' => 14,
                'items' => [
                    ['name' => 'TSH', 'price' => 330000],
                    ['name' => 'T3', 'price' => 150000],
                    ['name' => 'T4', 'price' => 150000],
                    ['name' => 'E.C.G', 'price' => 100000],
                    ['name' => 'Troponin', 'price' => 400000],
                    ['name' => 'Test Kehamilan', 'price' => 35000],
                    ['name' => 'Beta HCG Serum', 'price' => 450000],
                    ['name' => 'Testosteron', 'price' => 350000],
                    ['name' => 'Prolactin', 'price' => 580000],
                    ['name' => 'Progesteron', 'price' => 820000],
                    ['name' => 'LH', 'price' => 475000],
                    ['name' => 'FSH', 'price' => 475000],
                    ['name' => 'Cortisol', 'price' => 950000],
                    ['name' => 'Estradiol', 'price' => 420000],
                ]
            ],
            [
                'name' => 'Profile Tiroid',
                'code' => 'TIROID',
                'description' => 'Pemeriksaan fungsi tiroid',
                'sort_order' => 15,
                'items' => [
                    ['name' => 'T3', 'price' => 210000],
                    ['name' => 'T4', 'price' => 210000],
                    ['name' => 'FT3', 'price' => 230000],
                    ['name' => 'FT4', 'price' => 475000],
                    ['name' => 'TSIHS', 'price' => 475000],
                ]
            ],
            [
                'name' => 'Test Alergi',
                'code' => 'ALERGI',
                'description' => 'Pemeriksaan alergi',
                'sort_order' => 16,
                'items' => [
                    ['name' => 'Aero Allergen', 'price' => 140000],
                    ['name' => 'Kwantilamp', 'price' => 280000],
                    ['name' => 'RF Kwantilamp', 'price' => 140000],
                    ['name' => 'Kwantilamp', 'price' => 280000],
                    ['name' => 'CRP Kwantilamp', 'price' => 140000],
                    ['name' => 'ASO Kwantilamp', 'price' => 280000],
                    ['name' => 'Ana Test', 'price' => 575000],
                    ['name' => 'Sel LE', 'price' => 550000],
                    ['name' => 'LE Test', 'price' => 570000],
                    ['name' => 'Anti ds DNA', 'price' => 570000],
                ]
            ],
            [
                'name' => 'Immuno Serologi',
                'code' => 'IMMUNO',
                'description' => 'Pemeriksaan imunologi dan serologi',
                'sort_order' => 17,
                'items' => [
                    ['name' => 'VDRL Kwantilamp', 'price' => 140000],
                    ['name' => 'Kwantilamp', 'price' => 280000],
                    ['name' => 'TPHA Kwantilamp', 'price' => 140000],
                    ['name' => 'Kwantilamp', 'price' => 280000],
                    ['name' => 'Secret Uretm', 'price' => 250000],
                    ['name' => 'Widal Test', 'price' => 170000],
                    ['name' => 'IgG/IgM Anti Salmonella', 'price' => 250000],
                    ['name' => 'C.D.(G.D.S)', 'price' => 250000],
                    ['name' => 'Ig G Anti Dengue', 'price' => 250000],
                    ['name' => 'Ig M anti Dengue', 'price' => 280000],
                    ['name' => 'Tuberkulosis Salmonella Typhoid', 'price' => 280000],
                    ['name' => 'IgM', 'price' => 250000],
                    ['name' => 'NS1', 'price' => 250000],
                    ['name' => 'HIV', 'price' => 650000],
                    ['name' => 'Anti E Metode', 'price' => 250000],
                    ['name' => 'HIV ELISA', 'price' => 660000],
                    ['name' => 'IgM Anti Toxoplasma', 'price' => 500000],
                    ['name' => 'IgM/IgG Anti HSV1', 'price' => 650000],
                    ['name' => 'IgM/IgG Anti HSV II', 'price' => 750000],
                    ['name' => 'ICT - TB', 'price' => 260000],
                    ['name' => 'TORCH', 'price' => 2600000],
                    ['name' => 'IgG Anti Rubella', 'price' => 400000],
                    ['name' => 'IgM Anti Rubella', 'price' => 470000],
                    ['name' => 'IC1 Malaria', 'price' => 300000],
                    ['name' => 'Procalcitonin', 'price' => 975000],
                    ['name' => 'IgG Anti CMV', 'price' => 400000],
                    ['name' => 'IgM Anti CMV', 'price' => 475000],
                    ['name' => 'IgG/IgM ACA', 'price' => 920000],
                    ['name' => 'IgG/IgM Chlamydia', 'price' => 1750000],
                    ['name' => 'Helicobacter Pylory', 'price' => 420000],
                ]
            ],
            [
                'name' => 'Tumor Marker',
                'code' => 'TUMOR',
                'description' => 'Pemeriksaan penanda tumor',
                'sort_order' => 18,
                'items' => [
                    ['name' => 'AFP', 'price' => 330000],
                    ['name' => 'CEA', 'price' => 350000],
                    ['name' => 'L.D.H', 'price' => 330000],
                    ['name' => 'F.S.A', 'price' => 150000],
                    ['name' => 'S.C.C', 'price' => 100000],
                    ['name' => 'E.C.G', 'price' => 100000],
                    ['name' => 'Troponin', 'price' => 400000],
                    ['name' => 'IgE Total', 'price' => 400000],
                    ['name' => 'Besi Haemo', 'price' => 300000],
                ]
            ],
            [
                'name' => 'Analisa Cairan',
                'code' => 'CAIRAN',
                'description' => 'Pemeriksaan analisa cairan tubuh',
                'sort_order' => 19,
                'items' => [
                    ['name' => 'Benzidina Test', 'price' => 80000],
                    ['name' => 'Analisa Cairan', 'price' => 400000],
                    ['name' => 'Analisa Sperma', 'price' => 300000],
                    ['name' => 'Cairan Pleura', 'price' => 400000],
                    ['name' => 'Cairan Cerebrospinal', 'price' => 500000],
                    ['name' => 'Cytologi Cairan', 'price' => 430000],
                ]
            ],
            [
                'name' => 'Histopatologi',
                'code' => 'HISTO',
                'description' => 'Pemeriksaan histopatologi',
                'sort_order' => 20,
                'items' => [
                    ['name' => 'PAP Smear', 'price' => 325000],
                    ['name' => 'Histologi Jaringan kecil', 'price' => 550000],
                    ['name' => 'Besar', 'price' => 650000],
                ]
            ],
            [
                'name' => 'Mikrobiologi',
                'code' => 'MIKRO',
                'description' => 'Pemeriksaan mikrobiologi',
                'sort_order' => 21,
                'items' => [
                    ['name' => 'BTA', 'price' => 100000],
                    ['name' => 'BTA 3 X', 'price' => 300000],
                    ['name' => 'Direct Smear Gram', 'price' => 200000],
                    ['name' => 'Direct Smear K.O.H', 'price' => 250000],
                    ['name' => 'Secret Vagina', 'price' => 200000],
                    ['name' => 'Sekering Kanker', 'price' => 350000],
                    ['name' => 'C.E.A', 'price' => 350000],
                    ['name' => 'C.A. Stato Protein', 'price' => 600000],
                    ['name' => 'C.A. 15-3', 'price' => 500000],
                    ['name' => 'C.A. 19-9', 'price' => 650000],
                    ['name' => 'C.A. 125', 'price' => 580000],
                    ['name' => 'P.S.A', 'price' => 450000],
                    ['name' => 'Prostat Sperisifik Antigen', 'price' => 75000],
                ]
            ],
            [
                'name' => 'Test Narkoba',
                'code' => 'NARKOBA',
                'description' => 'Pemeriksaan narkoba dan obat-obatan',
                'sort_order' => 22,
                'items' => [
                    ['name' => 'Morphine', 'price' => 75000],
                    ['name' => 'T.H.C (Ganja, Hasis)', 'price' => 75000],
                    ['name' => 'Methamphetamin (Extasy, Shabu-sabu)', 'price' => 75000],
                    ['name' => 'Cocain', 'price' => 185000],
                    ['name' => 'Benzodizepines (Acetivan, Valium, Xanax)', 'price' => 185000],
                    ['name' => 'Paket Test Narkoba 6 Macam (Morphine, THC, amphetamin, cocaine, Methamphetamine, Benzodizepines)', 'price' => 185000],
                ]
            ],
        ];

        foreach ($categoriesData as $categoryData) {
            // Buat kategori
            $category = ServiceCategory::create([
                'name' => $categoryData['name'],
                'code' => $categoryData['code'],
                'description' => $categoryData['description'],
                'sort_order' => $categoryData['sort_order'],
            ]);

            // Buat items untuk kategori ini
            foreach ($categoryData['items'] as $index => $itemData) {
                ServiceItem::create([
                    'category_id' => $category->id,
                    'name' => $itemData['name'],
                    'code' => $itemData['code'] ?? null,
                    'price' => $itemData['price'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}