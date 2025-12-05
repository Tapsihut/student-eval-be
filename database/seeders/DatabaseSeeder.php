<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Subject;

class DatabaseSeeder extends Seeder
{
public function run(): void
{
    $adminNames = [
        ['Shiela Jane', 'Pena', 'ABE'],          // Agricultural and Biosystem Engineering
        ['Manuel', 'Tabada', 'BSECE'],          // BS Electronics Engineering
        ['Jef', 'Deleon', 'BSCE'],              // BS Civil Engineering
        ['Merly', 'Diegas', 'BindTech'],        // Bachelor in Industrial Technology
        ['Bernie S.', 'Balighot', 'BSIT'],      // BS Information Technology
        ['James Cloyd M.', 'Bustillo', 'BSIS'], // BS Information System
        ['Zaldy', 'Beloy', 'BTLEd'],            // Bachelor of Technology and Livelihood Education
        ['Jennifer', 'Burlado', 'BEEd/BSEd'],   // BEEd + BSEd
        ['Roxan', 'Remorosa', 'ABEL'],          // AB English Language
        ['Beverly O.', 'Galicia', 'BSA'],        // BSA
        ['Mark D.', 'Ytoc', 'BAT/DIFT'],        // BAT + DIFT
        ['Shahanneh', 'De Asis', 'BSAB'],       // BS Agribusiness
        ['Josephine', 'Madayag', 'BSAF'],       // BS Agroforestry
        ['Rodel B.', 'Azura', 'BSAM'],          // BS Applied Mathematics
        ['Juzavil J.', 'Juario', 'BSBIO'],      // BS Biology
        ['Carl Kenneth', 'Navarro', 'BSES'],    // BS Environmental Science
    ];

    // --- Main Admin ---
    User::factory()->create([
        'first_name' => 'Admin',
        'last_name'  => 'User',
        'role'       => 'admin',
        'email'      => 'admin@gmail.com',
        'password'   => Hash::make('123'),
        'course'     => 'ADMIN'
    ]);

    foreach ($adminNames as $index => $data) {

        $first = $data[0];
        $last = $data[1];
        $course = $data[2];

        $lastNameLower = strtolower(str_replace(' ', '', $last));

        User::factory()->create([
            'first_name' => $first,
            'last_name'  => $last,
            'role'       => 'admin',
            'email'      => $lastNameLower . '@gmail.com',
            'password'   => Hash::make($lastNameLower . '123'),
            'course'     => $course,
        ]);
    }

$studentNames = [
    ['Charles', 'Mosende', 'BSIS'],
    ['Jyrah', 'Amarante', 'BSIS'],
    ['Guirtfield', 'Roa', 'BSIT'],
    ['Jhana', 'Pactao-in', 'BSIS'],
    ['Diana Rose', 'Numeron', 'BSIS'],
    ['Junnel', 'Siarot', 'BSIS'],
    ['Dave', 'Dalayap', 'BSIT'],
    ['Jay', 'Dechosa', 'BSIS'],
    ['Joel', 'Abrinella', 'BSIT'],
    ['Micheal', 'Paloa', 'BSIT'],
    ['Apple Jean', 'Tejada', 'BSIT'],
    ['Christian Joe', 'Cagas', 'BSIT'],
    ['John Ril', 'Morales', 'BSIT'],
    ['User', 'Osin', 'BSIS'],
    ['Luis', 'Sedillo', 'BSIT'],
    ['Vinche', 'Cajes', 'BSIS'],
    ['John Kenneth C.', 'Andrade', 'BSIS'],
    ['Justin James', 'Puyo', 'BSIT'],
    ['Mark', 'Gementiza', 'BSIT'],
    ['Ive Jane', 'Sabando', 'BSIS'],
    ['Jasmine D.', 'Terante', 'BSIT'],
    ['Lovely', 'Divino', 'BSIT'],
    ['Jessica Ellaine', 'Navos', 'BSIT'],
    ['Lilibeth', 'Macasampon', 'BSIT'],
    ['Hazel', 'Ibarra', 'BSIT'],
    ['Charles', 'Caoile', 'BSIT'],
    ['Lovely Mae', 'Martinez', 'BSIT'],
    ['Christian G.', 'Adevino', 'BSIT'],
    ['Christian Angelo', 'Genotiva', 'BSIT'],
    ['Norie', 'Suganob', 'BSIT'],
    ['Zkyrt', 'Lunas', 'BSIT'],
    ['Earth Jhon Joseph', 'Plaza', 'BSIS'],
    ['Elijah James A.', 'Lota', 'BSIS'],
    ['Jay-Ar', 'Pongase', 'BSIS'],
    ['Luie Jay A.', 'Mondejar', 'BSIS'],
    ['Rufa', 'Casipong', 'BSIS'],
    ['Melchiyah', 'Catan', 'BSES'],
    ['Jazel J.', 'Corbella', 'BSES'],
    ['Jaime', 'Otang', 'BSIS'],
    ['Melvin', 'Clemente', 'BSIS'],
    ['Jasper', 'Enonaria', 'BSIS'],
    ['Jacob', 'Claro', 'BSIT'],
    ['Drix', 'Bebis', 'BSIS'],
    ['User', 'Samantha', 'BSIS'],
    ['Shek', 'Sentones', 'BSIS'],
   ['Saira', 'Condejar', 'BSIS'],
    ['Ronemar', 'Camacho', 'BSIS'],
    ['Jackelyn', 'Sarino', 'BSIS'],
    ['Micheal Angelo', 'Lofranco', 'BSIS'],
    ['Dave', 'Salvador', 'BSIS'],
    ['Daniela', 'Rocero', 'BSIT'],
    ['Erl Jhon', 'Pocon', 'BSIT'],
    ['Rolly', 'Junsay', 'BSIT'],
    ['Leo Renz', 'Parilla', 'BSIT'],
    ['Kimberly', 'Duites', 'BSIT'],
    ['Jeffer', 'Danguis', 'BSIT'],
    ['John Mark', 'Belar', 'BSIT'],
    ['Charlie', 'Lerio', 'BSIT'],
    ['Charles L.', 'Nocos', 'BSIT'],
    ['Eugene Carl', 'Catanus', 'BSIS'],
    ['Julie Rose', 'Balucos', 'BSIS'],
    ['Rose Jean', 'Rosales', 'ABEL'],
    ['Mery Clear', 'Jalalon', 'BSIS'],
    ['Joshua', 'Lumano', 'BSIS'],
    ['Kristian Jake', 'Espana', 'BSIS'],
    ['Cristina Mie', 'Visto', 'BSIT'],
    ['Mikylla', 'Barrios', 'ABEL'],
    ['Harold', 'Olor', 'BSIT'],
    ['Lycel', 'Baybanting', 'BSIT'],
    ['Richarol', 'Agunos', 'BSIT'],
    ['Charmin Fel', 'Sarona', 'BSIT'],
    ['Joel II', 'Abrenilla', 'BSIT'],
    ['Luis Lester', 'Bade', 'BSIT'],
    ['Reygie', 'Alongsagay', 'BSIT'],
    ['John Christopher', 'Fortaleza', 'BSIT'],
    ['Rowen', 'Elopre', 'BSECE'],
    ['Charie Mae', 'Naval', 'BSECE'],
    ['Anntoinette Emery', 'Saberdo', 'BSECE'],
    ['Ken Mark', 'Toloza', 'BSECE'],
    ['Markclito', 'Roxas', 'BSECE'],
    ['Michael Jann', 'Labadan', 'BindTech'],
    ['Jethro', 'Sabado', 'BindTech'],
    ['Rodel', 'Pal-ot', 'BindTech'],
    ['Ivan Jay', 'Almerol', 'BSABE'],
    ['Lemuel', 'Obids', 'BSABE'],
    ['Ronald', 'Estember', 'BSABE'],
    ['Rome Vincent', 'Porras', 'BSABE'],
    ['Nethaniel', 'Casal', 'BSABE'],
    ['Christian Jay', 'Rotaquio', 'BSABE'],
    ['Joseph James', 'Gaviola', 'BSABE'],
    ['Aldrake Troy', 'Lunasco', 'BSABE'],
    ['Kenth', 'Ampo', 'BSABE'],
    ['Mary Ann Grace', 'Janoto', 'BSABE'],
    ['Nizel', 'Libreta', 'BSABE'],
    ['Jonacel', 'Gargas', 'BSABE'],
    ['Rose Cynnedhryll', 'Pardillo', 'BSABE'],
    ['Jeah Mae', 'Caputi', 'BSABE'],
    ['Rechel Jane', 'Reponle', 'BSABE'],
    ['Gleamxerjiessa', 'Abenojar', 'BSIT'],
    ['Princess Yesha', 'Baba', 'BSIT'],
    ['Elsam', 'Lamoste', 'BSIT'],
    ['Harizon Mark', 'Dullo', 'BSIT'],
    ['Stephany', 'Bolay', 'BSIT'],
    ['Chasmelle', 'Gaspar', 'BSIT'],
    ['Joelia Jovel', 'Dela Cruz', 'BSIT'],
    ['John Paul', 'Casoles', 'BSIT'],
    ['Wenzyl Fretz', 'Devytol', 'BSIT'],
    ['Allysah Kate', 'Utbo', 'BSABE'],
    ['Albert James', 'Trilles', 'BSABE'],
    ['Gimo', 'Alonzo', 'BSABE'],
    ['Princess Rose', 'Napil', 'BSABE'],
    ['Ailyn', 'Torbiso', 'BSABE'],
    ['Prince John', 'Mondero', 'BSABE'],
    ['Christine Mae', 'Abuso', 'BSABE'],
    ['Eljunne', 'Canja', 'BSABE'],
    ['Chassen', 'Conson', 'BSABE'],
    ['James Marie', 'Dizon', 'BSABE'],
    ['Angela', 'Rafil', 'BSABE'],
    ['Neorilet', 'Trillo', 'BSABE'],
    ['Abegail Cara Jean', 'Galvez', 'BSABE'],
    ['Michaelvie', 'Benjao', 'BSABE'],
    ['Robelyn', 'Gerolaga', 'BSABE'],
    ['Gretchen', 'Lacre', 'BSABE'],
    ['Mid Jean', 'Igar', 'BSABE'],
    ['Randy', 'Gamba', 'BSABE'],
    ['Mark James', 'Toustamante', 'BSABE'],
    ['Novien Yacine', 'Batingal', 'BSABE'],
    ['Samerose', 'Estrellanes', 'BSABE'],
    ['Gabrelyn', 'Calisquez', 'BSABE'],
    ['Reep Vann Winkle', 'Sotto', 'BSABE'],
];

foreach ($studentNames as $index => $name) {
    $lastNameLower = strtolower(str_replace(' ', '', $name[1])); // remove spaces + lowercase
    $studentId = str_pad(rand(0, 9999999999999), 13, '0', STR_PAD_LEFT); // 13-digit random number

    User::factory()->create([
        'first_name' => $name[0],
        'last_name'  => $name[1],
        'student_id' => $studentId,
        'role'       => 'user',
        'email'      => $lastNameLower . '@gmail.com',
        'password'   => Hash::make($lastNameLower . '123'),
        'course'     => $name[2] ?? null,   // ← this now stores the course
    ]);
}
        // --- COURSES ---
        $abe = Course::create([
            'code' => 'ABE',
            'name' => 'Bachelor of Science in Agricultural and Biosystems Engineering',
            'description' => 'Engineering program focused on agriculture, biosystems, and environmental technologies.'
        ]);

        $bsece = Course::create([
            'code' => 'BSECE',
            'name' => 'Bachelor of Science in Electronics Engineering',
            'description' => 'Program covering electronics, communication systems, and digital technologies.'
        ]);

        $bsce = Course::create([
            'code' => 'BSCE',
            'name' => 'Bachelor of Science in Civil Engineering',
            'description' => 'Program focused on construction, structural design, and infrastructure engineering.'
        ]);

        $BindTech = Course::create([
            'code' => 'BindTech',
            'name' => 'Bachelor in Industrial Technology Major in Electronics Technology',
            'description' => 'Program focused on industrial work, applied technology, and technical operations.'
        ]);

        $bsit = Course::create([
            'code' => 'BSIT',
            'name' => 'Bachelor of Science in Information Technology',
            'description' => 'Focus on computing, networking, software development, and IT systems.'
        ]);

        $bsis = Course::create([
            'code' => 'BSIS',
            'name' => 'Bachelor of Science in Information Systems',
            'description' => 'Focus on IT-business integration, systems analysis, and information management.'
        ]);

        $btled = Course::create([
            'code' => 'BTLEd',
            'name' => 'Bachelor of Technology and Livelihood Education',
            'description' => 'Teacher education for TLE specialization and technical skills.'
        ]);

        $beed_bsed = Course::create([
            'code' => 'BEEd/BSEd',
            'name' => 'Bachelor of Elementary Education / Bachelor of Secondary Education',
            'description' => 'Teacher education program for basic and secondary education.'
        ]);

        $abel = Course::create([
            'code' => 'ABEL',
            'name' => 'AB English Language',
            'description' => 'Program focused on English language, literature, and communication.'
        ]);

        $bsa = Course::create([
            'code' => 'BSA',
            'name' => 'Bachelor of Science in Agriculture',
            'description' => 'Program focused on agricultural science, crop production, and agronomy.'
        ]);

        $bat_dift = Course::create([
            'code' => 'BAT/DIFT',
            'name' => 'Bachelor of Automotive Technology / Diploma in Industrial Facility Technology',
            'description' => 'Specialized technical programs in automotive and industrial facility technology.'
        ]);

        $bsab = Course::create([
            'code' => 'BSAB',
            'name' => 'Bachelor of Science in Agribusiness',
            'description' => 'Program combining agriculture, entrepreneurship, and business management.'
        ]);

        $bsaf = Course::create([
            'code' => 'BSAF',
            'name' => 'Bachelor of Science in Agroforestry',
            'description' => 'Program integrating agriculture and forestry for sustainable land use.'
        ]);

        $bsam = Course::create([
            'code' => 'BSAM',
            'name' => 'Bachelor of Science in Applied Mathematics',
            'description' => 'Program focused on modeling, computation, statistics, and mathematical applications.'
        ]);

        $bsbio = Course::create([
            'code' => 'BSBIO',
            'name' => 'Bachelor of Science in Biology',
            'description' => 'Program focused on biological science, research, and life sciences.'
        ]);

        $bses = Course::create([
            'code' => 'BSES',
            'name' => 'Bachelor of Science in Environmental Science',
            'description' => 'Program focused on environment, ecology, climate, and environmental management.'
        ]);

        // --- CURRICULUMS (Batch 2025–2029) ---
        $yearStart = 2025;
        $yearEnd = 2029;

        foreach ([
            $abe, $bsece, $bsce, $BindTech, $bsit, $bsis, $btled, $beed_bsed, 
            $abel, $bsa, $bat_dift, $bsab, $bsaf, $bsam, $bsbio, $bses
        ] as $course) {

            Curriculum::create([
                'course_id' => $course->id,
                'year_start' => $yearStart,
                'year_end' => $yearEnd,
                'is_active' => true,
            ]);
        }

        $BindTechSubjects = [

            // ============================
            // YEAR 1 — FIRST SEMESTER
            // ============================
            ['GE 01', 'Understanding the Self', 3, 0, 3, '1st', 1, []],
            ['GE 04', 'Mathematics in the Modern World', 3, 0, 3, '1st', 1, []],
            ['OSH 110', 'Occupational Safety and Health', 3, 0, 3, '1st', 1, []],
            ['CT 111', 'Electronic Devices 1', 2, 3, 5, '1st', 1, []],
            ['ITec 100', 'Industrial Drawing', 0, 2, 2, '1st', 1, []],
            ['MathSci 100', 'Comprehensive Mathematics', 2, 3, 5, '1st', 1, []],
            ['PATHFit 1', 'Movement Competency Training', 2, 0, 2, '1st', 1, []],
            ['NSTP 1', 'National Service Training Program 1', 3, 0, 3, '1st', 1, []],

            // ============================
            // YEAR 1 — SECOND SEMESTER
            // ============================
            ['CT 121', 'Electronic Devices 2', 2, 1, 3, '2nd', 1, ['CT 111']],
            ['CT 122', 'Electronic Communications 1', 2, 1, 3, '2nd', 1, ['CT 111']],
            ['GE 05', 'Purposive Communication', 3, 0, 3, '2nd', 1, []],
            ['ITec 101', 'Introduction to Information Technology', 3, 0, 3, '2nd', 1, []],
            ['ITec 102', 'Basic Electricity', 2, 1, 3, '2nd', 1, []],
            ['CT 123', 'Computer-Aided Design and Drafting (CADD)', 0, 2, 2, '2nd', 1, ['ITec 100']],
            ['PATHFit 2', 'Exercise-based Fitness Activities', 2, 0, 2, '2nd', 1, ['PATHFit 1']],
            ['NSTP 2', 'National Service Training Program 2', 3, 0, 3, '2nd', 1, ['NSTP 1']],

            // ============================
            // YEAR 2 — FIRST SEMESTER
            // ============================
            ['GE 02', 'Reading in the Philippine History', 3, 0, 3, '1st', 2, []],
            ['GE 03', 'The Contemporary World', 3, 0, 3, '1st', 2, []],
            ['CT 211', 'General Thermodynamics and Heat Transfer', 2, 1, 3, '1st', 2, ['MathSci 101','MathSci 102']],
            ['CT 212', 'Fundamental of Surveying', 3, 1, 4, '1st', 2, ['MathSci 101','MathSci 102']],
            ['CT 213', 'Geotechnical Building Systems: Theory, Design, and Analysis', 3, 1, 4, '1st', 2, ['MathSci 101','MathSci 102']],
            ['ITec 103', 'Computer Programming', 2, 1, 3, '1st', 2, []],
            ['ITec 104', 'Materials Technology Management', 3, 0, 3, '1st', 2, []],
            ['PATHFit 3', 'Dance/Sports/Martial Arts/Outdoor Activities 1', 2, 0, 2, '1st', 2, ['PATHFit 1','PATHFit 2']],

            // ============================
            // YEAR 2 — SECOND SEMESTER
            // ============================
            ['GE 06', 'Art Appreciation', 3, 0, 3, '2nd', 2, []],
            ['GE 07', 'Science, Technology and Society', 3, 0, 3, '2nd', 2, []],
            ['GE 08', 'Ethics', 3, 0, 3, '2nd', 2, []],
            ['CT 221', 'Structural Building Systems: Theory, Design, and Analysis', 3, 1, 4, '2nd', 2, ['CT 212']],
            ['CT 222', 'MEP and Sanitary Building Systems: Theory, Design, and Analysis', 2, 2, 4, '2nd', 2, ['CT 211','CT 212']],
            ['ITec 105', 'Quality Control and Assurance', 3, 0, 3, '2nd', 2, []],

            // ============================
            // YEAR 3 — FIRST SEMESTER
            // ============================
            ['GE 09', 'Life and Works of Rizal', 3, 0, 3, '1st', 3, []],
            ['GE-BIT 10', 'Gender and Society', 3, 0, 3, '1st', 3, []],
            ['GE-BIT 11', 'People and the Earth’s Ecosystem', 3, 0, 3, '1st', 3, []],
            ['CT 311', 'Hydrology and Hydraulics Building Systems: Theory, Design, and Analysis', 3, 3, 4, '1st', 3, ['CT 221','CT 222']],
            ['CT 312', 'Transportation Building Systems: Theory, Design, and Analysis', 3, 3, 4, '1st', 3, ['CT 221','CT 222']],
            ['ITec 107', 'Industrial Psychology', 3, 0, 3, '1st', 3, []],
            ['ITec 108', 'Basic Driving', 1, 3, 2, '1st', 3, []],
            ['PS 01', 'Project Study 1 with Intellectual Property Rights', 2, 3, 3, '1st', 3, ['Completed Professional Courses']],

            // ============================
            // YEAR 3 — SECOND SEMESTER
            // ============================
            ['GE-BIT 12', 'Disaster Risk Reduction and Management', 3, 0, 3, '2nd', 3, []],
            ['CT 321', 'Construction Economy (with Quantity Surveying)', 2, 0, 2, '2nd', 3, ['CT 311','CT 312']],
            ['CT 322', 'Construction Law, Ethics, Contracts, and Professional Practice', 3, 0, 3, '2nd', 3, ['CT 311','CT 312']],
            ['CT 323', 'Construction Materials and Testing, Method, and Project Management', 2, 3, 3, '2nd', 3, ['CT 311','CT 312']],
            ['ITec 109', 'Technopreneurship', 3, 0, 3, '2nd', 3, []],
            ['ITec 110', 'Production Management', 3, 0, 3, '2nd', 3, []],
            ['ITec 111', 'Foreign Language', 3, 0, 3, '2nd', 3, []],
            ['PS 02', 'Project Study 2', 2, 3, 3, '2nd', 3, ['PS 01']],

            // ============================
            // YEAR 4 — FIRST SEMESTER
            // ============================
            ['SIP 01', 'Student Internship Program 1', 0, 600, 6, '1st', 4, ['Completed Academic Requirements']],

            // ============================
            // YEAR 4 — SECOND SEMESTER
            // ============================
            ['SIP 02', 'Student Internship Program 2', 0, 600, 6, '2nd', 4, ['SIP 01']],

        ];

        //Bachelor of Science in Civil Engineering//
        $bseceSubjects = [
            // ============================
            // YEAR 1 — FIRST SEMESTER
            // ============================
            ['ENGGMATH108', 'Calculus 1', 3, 0, 3, '1st', 1, []],
            ['GE01', 'Understanding the Self', 3, 0, 3, '1st', 1, []],
            ['GE04', 'Mathematics in the Modern World', 3, 0, 3, '1st', 1, []],
            ['NATSCI100', 'Chemistry for Engineers', 3, 3, 4, '1st', 1, []],
            ['ACE100', 'Computer Programming', 0, 6, 2, '1st', 1, []],
            ['ES101', 'Computer Aided Drafting', 0, 3, 1, '1st', 1, []],
            ['GE02', 'Readings in Philippine History', 3, 0, 3, '1st', 1, []],
            ['PATHFIT1', 'Movement Competency Training', 2, 0, 2, '1st', 1, []],
            ['NSTP1', 'National Service Training Program 1', 3, 0, 3, '1st', 1, []],

            // ============================
            // YEAR 1 — SECOND SEMESTER
            // ============================
            ['ENGGMATH112', 'Calculus 2', 3, 0, 3, '2nd', 1, ['ENGGMATH108']],
            ['NATSCI101', 'Physics for Engineers', 3, 3, 4, '2nd', 1, ['ENGGMATH108']],
            ['ACE101', 'Physics 2', 3, 3, 4, '2nd', 1, ['ENGGMATH108', 'NATSCI101']],
            ['ENVISCI100', 'Environmental Science and Engineering', 3, 0, 3, '2nd', 1, ['NATSCI100']],
            ['ECEIC100', 'Integration Course 1 - Elex Shop', 0, 3, 1, '2nd', 1, []],
            ['GE03', 'The Contemporary World', 3, 0, 3, '2nd', 1, []],
            ['GE05', 'Purposive Communication', 3, 0, 3, '2nd', 1, []],
            ['PATHFIT2', 'Exercise-based Fitness Activities', 2, 0, 2, '2nd', 1, ['PATHFIT1']],
            ['NSTP2', 'National Service Training Program 2', 3, 0, 3, '2nd', 1, ['NSTP1']],

            // ============================
            // YEAR 2 — FIRST SEMESTER
            // ============================
            ['ENGGMATH122', 'Differential Equations', 3, 0, 3, '1st', 2, ['ENGGMATH112']],
            ['ACE102', 'Circuits 1', 3, 3, 4, '1st', 2, ['ENGGMATH112','NATSCI101','ACE101']],
            ['ACE103', 'Material Science and Engineering', 3, 0, 3, '1st', 2, ['NATSCI100']],
            ['ECEIC101', 'Integration Course 2 - Computer & Commercial Technology Systems', 0, 3, 1, '1st', 2, ['ECEIC100']],
            ['ES107', 'Engineering Management', 2, 0, 2, '1st', 2, []],
            ['GE06', 'Art Appreciation', 3, 0, 3, '1st', 2, []],
            ['GE07', 'Science, Technology and Society', 3, 0, 3, '1st', 2, []],
            ['GE08', 'Ethics', 3, 0, 3, '1st', 2, []],
            ['GE09', 'Life and Works of Rizal', 3, 0, 3, '1st', 2, []],
            ['PATHFIT3', 'Dance, Sports, Martial Arts, Group Exercise 1', 2, 0, 2, '1st', 2, ['PATHFIT2']],

            // ============================
            // YEAR 2 — SECOND SEMESTER
            // ============================
            ['ENGGMATH123', 'Engineering Data Analysis', 2, 3, 3, '2nd', 2, ['ENGGMATH108']],
            ['ECE100', 'Electronics 1: Electronic Devices and Circuits', 3, 3, 4, '2nd', 2, ['ACE103','ACE102']],
            ['ECE101', 'Electromagnetics', 4, 0, 4, '2nd', 2, ['ENGGMATH122']],
            ['ECE102', 'Advanced Engineering Mathematics for ECE', 3, 3, 4, '2nd', 2, ['ENGGMATH122']],
            ['ECE103', 'Methods of Research', 3, 0, 3, '2nd', 2, ['GE05']],
            ['ACE104', 'Circuits 2', 3, 3, 4, '2nd', 2, ['ACE102']],
            ['ECEIC102', 'Integration Course 3 - GIS Applications', 0, 3, 1, '2nd', 2, ['ES101']],
            ['PATHFIT4', 'Dance, Sports, Martial Arts, Group Exercise, Outdoor and Adventure Activities 2', 2, 0, 2, '2nd', 2, ['PATHFIT1','PATHFIT2']],

            // ============================
            // YEAR 3 — FIRST SEMESTER
            // ============================
            ['ECE104', 'Signals, Spectra, Signal Processing', 3, 3, 4, '1st', 3, ['ECE102']],
            ['ECE105', 'Electronic 2: Electronic Circuit Analysis and Design', 3, 3, 4, '1st', 3, ['ECE100','ACE104']],
            ['ECE106', 'Communication 1: Principle of Communication System', 3, 3, 4, '1st', 3, ['ECE100']],
            ['GECECE100', 'Numerical Solutions to ECE Problems', 3, 3, 4, '1st', 3, ['ECE102']],
            ['ES106', 'Engineering Economics', 3, 0, 3, '1st', 3, ['ENGGMATH123']],
            ['GE10', 'Philippine Indigenous Communities', 3, 0, 3, '1st', 3, []],
            ['GE13', 'Living in the IT Era', 3, 0, 3, '1st', 3, []],

            // ============================
            // YEAR 3 — SECOND SEMESTER
            // ============================
            ['ECE107', 'Electronic 3:Electronic System and Design', 3, 3, 4, '2nd', 3, ['ECE105']],
            ['ECE108', 'Digital Electronic 1:Logic Circuits Design and Switching Theory', 3, 3, 4, '2nd', 3, ['ECE105']],
            ['ECE109', 'Communication 2: Modulation & Coding Techniques', 3, 3, 4, '2nd', 3, ['ECE106']],
            ['ECE110', 'Feedback and Control Systems', 3, 3, 4, '2nd', 3, ['GECECE100','ECE104']],
            ['TECHNO101', 'Technopreneurship 101', 3, 0, 3, '2nd', 3, ['3rdYearStanding']],
            ['GE14', 'The Entrepreneurial Mind', 3, 0, 3, '2nd', 3, []],
            ['ECEEL100', 'Power Electronics 1: Renewable Energy System', 3, 3, 4, '2nd', 3, ['ACE104']],

                // THIRD YEAR — SUMMER
            ['ECE111', 'On-the-Job Training (240 Hours)', 0, 3, 3, 'summer', 3, ['3rdYearStanding']],

            // ============================
            // YEAR 4 — FIRST SEMESTER
            // ============================
            ['ECE112', 'Digital Electronics 2: Microprocessor & Microcontroller Systems Design', 3, 3, 4, '1st', 4, ['ECE108']],
            ['ECE113', 'Communication 3: Data Communications', 3, 3, 4, '1st', 4, ['ECE109']],
            ['ECE114', 'Communication 4: Transmission Media & Antenna System', 3, 3, 4, '1st', 4, ['ECE109']],
            ['THESIS100', 'Thesis Outline Preparation and Presentation', 0, 3, 1, '1st', 4, ['ES106','ECE107']],
            ['ECEEL101', 'Power Electronics 2: Advanced Power Supply System', 3, 3, 4, '1st', 4, ['ECE101','ECE107']],
            ['ECEEL102', 'Advanced Instrumentation and Control', 3, 3, 4, '1st', 4, []],
            ['ECEIC103', 'Integration Course 4 - Internet of Things', 0, 3, 1, '1st', 4, ['ECE112','ECE113']],

            // ============================
            // YEAR 4 — SECOND SEMESTER
            // ============================
            ['ECE115', 'ECE Laws, Contracts, Ethics, Standards and Safety', 3, 0, 3, '2nd', 4, ['3rdYearStanding']],
            ['ECE116', 'Seminars / Colloquium', 0, 3, 1, '2nd', 4, ['4thYearStanding']],
            ['THESIS101', 'Conduct of Thesis and Final Presentation', 0, 9, 3, '2nd', 4, ['THESIS100']],
            ['ECEIC104', 'Integration Course 4 - Advance Electronics Engineering Technology', 0, 9, 3, '2nd', 4, ['4thYearStanding']],
            ['ECEIC105', 'Integration Course 5 - Advance Communications Engineering Technology', 0, 9, 3, '2nd', 4, ['4thYearStanding']],

        ];
        // --- BSIT Subjects 1st–4th Year (template) ---
   $bsitSubjects = [

                // ============================
                // YEAR 1 — FIRST SEMESTER
                // LEC/LAB BLOCK USED: 2-3, 2-3, 3-0, 3-0, 3-0, 3-0, 2-0
                // ============================
                ['ITCC100', 'Introduction to Computing',2, 3, 3, '1st', 1, []],
                ['ITCC101', 'Fundamentals of Programming',2, 3, 3, '1st', 1, []],
                ['GE01', 'Understanding the Self',3, 0, 3, '1st', 1, []],
                ['GE04', 'Mathematics in the Modern World',3, 0, 3, '1st', 1, []],
                ['GE05', 'Purposive Communication',3, 0, 3, '1st', 1, []],
                ['ITELEC100', 'Discrete Mathematics for IT',3, 0, 3, '1st', 1, []],
                ['PATHFit1', 'Movement Competency Training',2, 0, 2, '1st', 1, []],
                ['NSTP1', 'National Service Training Program 1',0, 0, 3, '1st', 1, []], // extra subject, block exhausted

                // ============================
                // YEAR 1 — SECOND SEMESTER
                // LEC/LAB BLOCK USED: 2-3, 2-3, 2-3, 2-3, 3-0, 3-0, 3-0, 2-0
                // ============================
                ['ITCC102', 'Intermediate Programming',2, 3, 3, '2nd', 1, ['ITCC101']],
                ['ITPC100', 'Fundamentals of Web Development',2, 3, 3, '2nd', 1, []],
                ['ITPC101', 'Computer System Servicing',2, 3, 3, '2nd', 1, []],
                ['ITPC112', 'Visual Graphics Design',2, 3, 3, '2nd', 1, []],
                ['GE07', 'Science, Technology and Society',3, 0, 3, '2nd', 1, []],
                ['GE09', 'Life and Works of Rizal',3, 0, 3, '2nd', 1, []],
                ['GE08', 'Ethics',3, 0, 3, '2nd', 1, []],
                ['PATHFit2', 'Exercise-based Fitness Activities',2, 0, 2, '2nd', 1, ['PATHFit1']],
                ['NSTP2', 'National Service Training Program 2',0, 0, 3, '2nd', 1, ['NSTP1']], // extra — block exhausted

                // ============================
                // YEAR 2 — FIRST SEMESTER
                // BLOCK: 2-3, 2-3, 2-3, 2-3, 3-0, 3-0, 3-0, 2-0
                // ============================
                ['ITCC103', 'Data Structures & Algorithms',2, 3, 3, '1st', 2, ['ITCC102']],
                ['ITPC102', 'Object-Oriented Programming',2, 3, 3, '1st', 2, ['ITCC102']],
                ['ITPC103', 'Database Management System',2, 3, 3, '1st', 2, ['ITCC101']],
                ['ITPC104', 'Computer Networking 1 - Fundamentals',2, 3, 3, '1st', 2, ['ITPC101']],
                ['GE02', 'Readings in the Philippine History',3, 0, 3, '1st', 2, []],
                ['GE10', 'Philippine Indigenous Community',3, 0, 3, '1st', 2, []],
                ['GE03', 'The Contemporary World',3, 0, 3, '1st', 2, []],
                ['PATHFit3', 'Dance, Sports, Martial Arts, Group Exercise 1',2, 0, 2, '1st', 2, ['PATHFit2']],

                // ============================
                // YEAR 2 — SECOND SEMESTER
                // BLOCK: 2-3, 2-3, 2-3, 2-3, 2-3, 3-0, 2-3, 2-0
                // ============================
                ['ITCC104', 'Information Management - RDBMS',2, 3, 3, '2nd', 2, ['ITPC103']],
                ['ITPC105', 'Event Driven Programming',2, 3, 3, '2nd', 2, ['ITCC103','ITPC102','ITPC103']],
                ['ITPC106', 'Advanced Web Development',2, 3, 3, '2nd', 2, ['ITPC100','ITPC103']],
                ['ITPC107', 'Human Computer Interaction',2, 3, 3, '2nd', 2, ['ITPC112','ITCC101']],
                ['ITPC108', 'Python Programming',2, 3, 3, '2nd', 2, ['ITCC101']],
                ['GE06', 'Art Appreciation',3, 0, 3, '2nd', 2, []],
                ['GEIT11', 'Sensors and Interfacing',2, 3, 3, '2nd', 2, ['ITCC101']],
                ['PATHFit4', 'Dance, Sports, Martial Arts, Group Exercise 2',2, 0, 2, '2nd', 2, ['PATHFit3']],

                // ============================
                // YEAR 3 — FIRST SEMESTER
                // BLOCK: 2-3, 2-3, 2-3, 2-3, 2-3, 3-0, 3-0
                // ============================
                ['ITPC109', 'Mobile Application Development',2, 3, 3, '1st', 3, ['ITCC104','ITPC105']],
                ['ITPC110', 'Computer Networking 2 - Advanced',2, 3, 3, '1st', 3, ['ITPC104']],
                ['ITPC111', 'System Analysis and Design',2, 3, 3, '1st', 3, ['ITCC104','ITPC105','ITPC106','ITPC107']],
                ['ITELEC101', 'Basic Accounting',2, 3, 3, '1st', 3, ['ITCC100']],
                ['ITELEC102', 'Multimedia Development',2, 3, 3, '1st', 3, ['ITPC112']],
                ['ITPC113', 'Technopreneurship',3, 0, 3, '1st', 3, ['ITPC107']],
                ['ITPC114', 'Social and Professional Issues',3, 0, 3, '1st', 3, []],

                // ============================
                // YEAR 3 — SECOND SEMESTER
                // BLOCK: 2-3, 2-3, 2-3, 2-3, 3-0, 11-12 ??? (block is short)
                // ============================
                ['ITCC105', 'Application Development and Emerging Technologies',2, 3, 3, '2nd', 3, ['ITPC109']],
                ['ITPC115', 'Information and Assurance Security 1',2, 3, 3, '2nd', 3, ['ITPC110','ITCC104']],
                ['GEIT12', 'GIS Applications',2, 3, 3, '2nd', 3, ['ITCC102']],
                ['ITPC116', 'System Integration and Architecture',2, 3, 3, '2nd', 3, ['ITPC108','ITPC109','ITPC111']],
                ['ITPC117', 'Capstone Project 1',3, 0, 3, '2nd', 3, ['ITPC111']],

                // ============================
                // YEAR 4 — FIRST SEMESTER
                // BLOCK: 2-3, 2-3, 2-3, 3-0
                // ============================
                ['ITELEC103', 'Simulation and Modeling',2, 3, 3, '1st', 4, ['ITPC108']],
                ['ITPC118', 'Systems Administration and Maintenance',2, 3, 3, '1st', 4, ['ITPC111']],
                ['ITPC119', 'Information and Assurance Security 2',2, 3, 3, '1st', 4, ['ITPC115']],
                ['ITPC120', 'Capstone Project 2',3, 0, 3, '1st', 4, ['ITPC117']],

                // ============================
                // YEAR 4 — SECOND SEMESTER (OJT)
                // BLOCK: 0-27
                // ============================
                ['ITPC121', 'On-the-job Training (486 Hours)',0, 27, 6, '2nd', 4, []],

            ];

        // --- BSIS Subjects 1st–4th Year (template) ---
        $bsisSubjects = [
            // Year 1
            ['ITCC100', 'Introduction to Computing', 2, 3, 3, '1st', 1, []],
            ['ITCC101', 'Fundamentals of Programming', 2, 3, 3, '1st', 1, []],
            ['IS100', 'Fundamentals of Information Systems', 3, 0, 3, '1st', 1, []],
            ['GE01', 'Understanding the Self', 3, 0, 3, '1st', 1, []],
            ['GE04', 'Mathematics in the Modern World', 3, 0, 3, '1st', 1, []],
            ['GE05', 'Purposive Communication', 3, 0, 3, '1st', 1, []],
            ['PATHFIT1', 'Movement Competency Training', 2, 0, 2, '1st', 1, []],
            ['NSTP1', 'National Service Training Program 1', 3, 0, 3, '1st', 1, []],

            ['ITCC102', 'Intermediate Programming', 2, 3, 3, '2nd', 1, ['ITCC100', 'ITCC101']],
            ['IS101', 'Organization and Management Concepts', 3, 0, 3, '2nd', 1, ['IS100']],
            ['IS102', 'Business Process Design and Implementation', 3, 0, 3, '2nd', 1, ['IS100']],
            ['GE07', 'Science, Technology and Society', 3, 0, 3, '2nd', 1, []],
            ['GE09', 'Life and Works of Rizal', 3, 0, 3, '2nd', 1, []],
            ['GE10', 'Philippine Indigenous Communities', 3, 0, 3, '2nd', 1, []],
            ['PATHFIT2', 'Exercise-based Fitness Activities', 2, 0, 2, '2nd', 1, ['PATHFIT1']],
            ['NSTP2', 'National Service Training Program 2', 3, 0, 3, '2nd', 1, ['NSTP1']],


            // Year 2
            ['ITCC103', 'Data Structures & Algorithms', 2, 3, 3, '1st', 2, ['ITCC102']],
            ['IS103', 'Human Computer Interaction', 2, 3, 3, '1st', 2, ['IS101', 'ITCC101']],
            ['ISELEC100', 'Contemporary World', 2, 3, 3, '1st', 2, ['IS101']],
            ['IS104', 'Supply Chain Management', 3, 0, 3, '1st', 2, ['IS102']],
            ['ISELEC101', 'Ethics', 3, 0, 3, '1st', 2, []],
            ['ISELEC102', 'Data Analytics', 2, 0, 3, '1st', 2, []],
            ['PATHFIT3', 'Dance, Sports, Martial Arts Group Exercise, Outdoor', 2, 0, 2, '1st', 2, ['PATHFIT2']],

            ['ITCC104', 'Information Management (DBMS)', 2, 3, 3, '2nd', 2, ['ITCC102']],
            ['IS105', 'IS Project Management', 3, 0, 3, '2nd', 2, ['IS101', 'IS103']],
            ['IS106', 'Professional Issues in Information Systems', 3, 0, 3, '2nd', 2, ['IS100']],
            ['IS107', 'IS Strategy, Management and Acquisition', 3, 0, 3, '2nd', 2, ['IS101', 'IS103']],
            ['GE02', 'Readings in the Philippine History', 3, 0, 3, '2nd', 2, []],
            ['GE06', 'Art Appreciation', 3, 0, 3, '2nd', 2, []],
            ['PATHFIT4', 'Dance, Sports, Martial Arts Group Exercise, Outdoor and Adventure', 2, 0, 2, '2nd', 2, ['PATHFIT3']],

            // Year 3
            ['IS108', 'System Analysis and Design', 2, 3, 3, '1st', 3, ['IS105', 'ITCC104']],
            ['IS109', 'IT Infrastructure and Network', 2, 3, 3, '1st', 3, ['IS107']],
            ['IS110', 'Financial Management', 2, 3, 3, '1st', 3, ['IS104']],
            ['ISELEC103', 'Fundamentals of Web Development', 2, 3, 3, '1st', 3, ['ITCC101', 'ITCC102']],
            ['IS111', 'IT Service Management', 3, 0, 3, '1st', 3, ['IS105', 'IS107']],
            ['GEIS100', 'Financial and Managerial Accounting for IS', 3, 0, 3, '1st', 3, []],
            ['IS112', 'Enterprise Resource Planning', 3, 0, 3, '1st', 3, ['IS105']],

            ['ITCC105', 'Application Development and Emerging Technologies', 2, 3, 3, '2nd', 3, ['IS108']],
            ['IS113', 'Enterprise Architecture', 3, 0, 3, '2nd', 3, ['IS112']],
            ['IS114', 'Evaluation of Business Performance', 3, 0, 3, '2nd', 3, ['IS108', 'IS111']],
            ['IS115', 'E-Commerce', 2, 3, 3, '2nd', 3, ['IS101']],
            ['GE13', 'Living in the IT Era', 3, 0, 3, '2nd', 3, []],
            ['ISELEC104', 'Data Process Outsourcing', 2, 3, 3, '2nd', 3, ['IS112']],
            ['ISCAP100', 'Capstone Project 1', 3, 0, 3, '2nd', 3, ['IS108']],

            // Year 4
            ['IS116', 'IT Audit and Controls', 3, 0, 3, '1st', 4, ['IS114']],
            ['IS117', 'IT Security and Management', 2, 3, 3, '1st', 4, ['IS109']],
            ['ISELEC105', 'Web Development Application (MVC)', 2, 3, 3, '1st', 4, ['ISELEC103']],
            ['GE14', 'Entrepreneurial Mind', 3, 0, 3, '1st', 4, []],
            ['ISCAP101', 'Capstone Project 2', 3, 0, 3, '1st', 4, ['ISCAP100']],
            
            ['OJT100', 'On-the-Job Training (486 Hours)', 0, 0, 6, '2nd', 4, ['75% Earned Units']],

        ];  

        $this->insertSubjects($bsece->id, $bseceSubjects);
        $this->insertSubjects($BindTech->id, $BindTechSubjects);
        $this->insertSubjects($bsit->id, $bsitSubjects);
        $this->insertSubjects($bsis->id, $bsisSubjects);

        $this->command->info('✅ Seeded full 4-year curriculum.');
    }

private function insertSubjects($curriculumId, $subjects)
{
    $map = [];

    // First pass — insert subjects
    foreach ($subjects as [$code, $name, $lec, $lab, $units, $sem, $year, $prereqs]) {
        $s = Subject::create([
            'curriculum_id' => $curriculumId,
            'code'          => $code,
            'name'          => $name,
            'lec'           => $lec,
            'lab'           => $lab,
            'units'         => $units,
            'semester'      => $sem,
            'year_level'    => $year,
        ]);

        $map[$code] = $s;
    }

    // Second pass — attach prerequisites
    foreach ($subjects as [$code, $name, $lec, $lab, $units, $sem, $year, $prereqs]) {
        if (!empty($prereqs)) {
            $ids = array_filter(array_map(
                fn($p) => $map[$p]->id ?? null,
                $prereqs
            ));

            if (!empty($ids)) {
                $map[$code]->prerequisites()->attach($ids);
            }
        }
    }
}

}
