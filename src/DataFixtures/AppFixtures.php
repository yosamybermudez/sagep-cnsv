<?php

namespace App\DataFixtures;

use App\Entity\Municipio;
use App\Entity\Provincia;
use App\Entity\Rol;
use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Json;


class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->encoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $roles = [
            "ROLE_ADMINISTRADOR_SISTEMA" => "Administrador del Sistema",
            "ROLE_ADMINISTRATOR_NEGOCIO" => "Administrador del Negocio",
            "ROLE_ESPECIALISTA_PRINCIPAL" => "Especialista principal",
            "ROLE_ESPECIALISTA" => "Especialista",
            "ROLE_USUARIO_ESTANDAR" => "Usuario estándar"
        ];

        foreach ($roles as $key => $value) {
            if (!$manager->getRepository(Rol::class)->findByIdentificador([$key])) {
                $rol = new Rol();
                $rol->setIdentificador($key);
                $rol->setNombre($value);
                $rol->setDescripcion('');
                $manager->persist($rol);
                $manager->flush();
            }
        }

        $usuario = new Usuario();
        if (!$manager->find(Usuario::class, 1)) {
            $usuario->setUsername('admin');
            $usuario->setRoles(["ROLE_ADMINISTRADOR_SISTEMA"]);
            $usuario->setPassword($this->encoder->encodePassword($usuario, 'admin'));
            $usuario->setNombreCompleto('Administrador del Sistema');
            $usuario->setEmail('admin@empresa.cu');
            $usuario->setValid(true);
            $usuario->setDeleted(false);
            $usuario->setAdmin(true);
            $manager->persist($usuario);

            $manager->flush();
        }

        $provincias = $this->allProvincias();
        foreach ($provincias as $key => $prov){
            $provincia = new Provincia();
            $provincia->setId($prov["id"]);
            $provincia->setNombre($prov["nombre"]);
            $provincia->setAbreviatura($prov["abreviatura"]);
            $manager->persist($provincia);
        }
        $manager->flush();

        $municipios = $this->allMunicipios();
        foreach ($municipios as $key => $munic){
            $municipio = new Municipio();
            $municipio->setId($munic["id"]);
            $municipio->setNombre($munic["nombre"]);
            $provincia = $manager->getRepository(Provincia::class)->find($munic["provincia_id"]);
            $municipio->setProvincia($provincia);
            $manager->persist($municipio);
        }
        $manager->flush();

    }

    private function allProvincias(){
        return array(
            0 => array('id' => '27', 'nombre' => 'Cienfuegos', 'abreviatura' => 'CFG'),
            1 => array('id' => '40', 'nombre' => 'Isla de la Juventud', 'abreviatura' => 'ISJ'),
            2 => array('id' => '25', 'nombre' => 'Matanzas', 'abreviatura' => 'MTZ'),
            3 => array('id' => '24', 'nombre' => 'Mayabeque', 'abreviatura' => 'MAY'),
            4 => array('id' => '32', 'nombre' => 'Holguín', 'abreviatura' => 'HLG'),
            5 => array('id' => '28', 'nombre' => 'Sancti Spíritus', 'abreviatura' => 'SSP'),
            6 => array('id' => '22', 'nombre' => 'Artemisa', 'abreviatura' => 'ART'),
            7 => array('id' => '34', 'nombre' => 'Santiago de Cuba', 'abreviatura' => 'SCU'),
            8 => array('id' => '21', 'nombre' => 'Pinar del Río', 'abreviatura' => 'PRI'),
            9 => array('id' => '33', 'nombre' => 'Granma', 'abreviatura' => 'GRM'),
            10 => array('id' => '30', 'nombre' => 'Camagüey', 'abreviatura' => 'CMG'),
            11 => array('id' => '29', 'nombre' => 'Ciego de Ávila', 'abreviatura' => 'CAV'),
            12 => array('id' => '23', 'nombre' => 'La Habana', 'abreviatura' => 'HAB'),
            13 => array('id' => '31', 'nombre' => 'Las Tunas', 'abreviatura' => 'LTU'),
            14 => array('id' => '35', 'nombre' => 'Guantánamo', 'abreviatura' => 'GTM'),
            15 => array('id' => '26', 'nombre' => 'Villa Clara', 'abreviatura' => 'VCL'),
        );
    }

    private function allMunicipios(){
        return array(
            0 => array('id' => '2613', 'provincia_id' => '26', 'nombre' => 'Manicaragua'),
            1 => array('id' => '3403', 'provincia_id' => '34', 'nombre' => 'San Luis'),
            2 => array('id' => '3509', 'provincia_id' => '35', 'nombre' => 'Guantánamo'),
            3 => array('id' => '2907', 'provincia_id' => '29', 'nombre' => 'Majagua'),
            4 => array('id' => '3211', 'provincia_id' => '32', 'nombre' => 'Mayarí'),
            5 => array('id' => '2409', 'provincia_id' => '24', 'nombre' => 'Melena del Sur'),
            6 => array('id' => '3206', 'provincia_id' => '32', 'nombre' => 'Holguín'),
            7 => array('id' => '2510', 'provincia_id' => '25', 'nombre' => 'Ciénaga de Zapata'),
            8 => array('id' => '2612', 'provincia_id' => '26', 'nombre' => 'Ranchuelo'),
            9 => array('id' => '2311', 'provincia_id' => '23', 'nombre' => 'Marianao'),
            10 => array('id' => '2405', 'provincia_id' => '24', 'nombre' => 'Madruga'),
            11 => array('id' => '2706', 'provincia_id' => '27', 'nombre' => 'Cumanayagua'),
            12 => array('id' => '2901', 'provincia_id' => '29', 'nombre' => 'Chambas'),
            13 => array('id' => '2707', 'provincia_id' => '27', 'nombre' => 'Cienfuegos'),
            14 => array('id' => '2701', 'provincia_id' => '27', 'nombre' => 'Aguada de Pasajeros'),
            15 => array('id' => '3105', 'provincia_id' => '31', 'nombre' => 'Las Tunas'),
            16 => array('id' => '2211', 'provincia_id' => '22', 'nombre' => 'San Cristóbal'),
            17 => array('id' => '2210', 'provincia_id' => '22', 'nombre' => 'Candelaria'),
            18 => array('id' => '2313', 'provincia_id' => '23', 'nombre' => 'Boyeros'),
            19 => array('id' => '2601', 'provincia_id' => '26', 'nombre' => 'Corralillo'),
            20 => array('id' => '2905', 'provincia_id' => '29', 'nombre' => 'Ciro Redondo'),
            21 => array('id' => '3005', 'provincia_id' => '30', 'nombre' => 'Nuevitas'),
            22 => array('id' => '2504', 'provincia_id' => '25', 'nombre' => 'Colón'),
            23 => array('id' => '2703', 'provincia_id' => '27', 'nombre' => 'Palmira'),
            24 => array('id' => '3204', 'provincia_id' => '32', 'nombre' => 'Antilla'),
            25 => array('id' => '2807', 'provincia_id' => '28', 'nombre' => 'Sancti Spíritus'),
            26 => array('id' => '2101', 'provincia_id' => '21', 'nombre' => 'Sandino'),
            27 => array('id' => '2609', 'provincia_id' => '26', 'nombre' => 'Santa Clara'),
            28 => array('id' => '2202', 'provincia_id' => '22', 'nombre' => 'Mariel'),
            29 => array('id' => '2604', 'provincia_id' => '26', 'nombre' => 'Encrucijada'),
            30 => array('id' => '3507', 'provincia_id' => '35', 'nombre' => 'San Antonio del Sur'),
            31 => array('id' => '2702', 'provincia_id' => '27', 'nombre' => 'Rodas'),
            32 => array('id' => '2108', 'provincia_id' => '21', 'nombre' => 'Pinar del Río'),
            33 => array('id' => '3310', 'provincia_id' => '33', 'nombre' => 'Pilón'),
            34 => array('id' => '3107', 'provincia_id' => '31', 'nombre' => 'Colombia'),
            35 => array('id' => '2401', 'provincia_id' => '24', 'nombre' => 'Bejucal'),
            36 => array('id' => '3004', 'provincia_id' => '30', 'nombre' => 'Minas'),
            37 => array('id' => '2310', 'provincia_id' => '23', 'nombre' => 'Cerro'),
            38 => array('id' => '2806', 'provincia_id' => '28', 'nombre' => 'Trinidad'),
            39 => array('id' => '3201', 'provincia_id' => '32', 'nombre' => 'Gibara'),
            40 => array('id' => '3301', 'provincia_id' => '33', 'nombre' => 'Río Cauto'),
            41 => array('id' => '2107', 'provincia_id' => '21', 'nombre' => 'Consolación del Sur'),
            42 => array('id' => '2301', 'provincia_id' => '23', 'nombre' => 'Playa'),
            43 => array('id' => '3208', 'provincia_id' => '32', 'nombre' => 'Cacocum'),
            44 => array('id' => '2305', 'provincia_id' => '23', 'nombre' => 'Regla'),
            45 => array('id' => '2104', 'provincia_id' => '21', 'nombre' => 'Viñales'),
            46 => array('id' => '3008', 'provincia_id' => '30', 'nombre' => 'Camagüey'),
            47 => array('id' => '3210', 'provincia_id' => '32', 'nombre' => 'Cueto'),
            48 => array('id' => '2203', 'provincia_id' => '22', 'nombre' => 'Guanajay'),
            49 => array('id' => '2603', 'provincia_id' => '26', 'nombre' => 'Sagua la Grande'),
            50 => array('id' => '2411', 'provincia_id' => '24', 'nombre' => 'Quivicán'),
            51 => array('id' => '2902', 'provincia_id' => '29', 'nombre' => 'Morón'),
            52 => array('id' => '2209', 'provincia_id' => '22', 'nombre' => 'Artemisa'),
            53 => array('id' => '3504', 'provincia_id' => '35', 'nombre' => 'Baracoa'),
            54 => array('id' => '2207', 'provincia_id' => '22', 'nombre' => 'Güira de Melena'),
            55 => array('id' => '2501', 'provincia_id' => '25', 'nombre' => 'Matanzas'),
            56 => array('id' => '2302', 'provincia_id' => '23', 'nombre' => 'Plaza de la Revolución'),
            57 => array('id' => '2402', 'provincia_id' => '24', 'nombre' => 'San José de las Lajas'),
            58 => array('id' => '2404', 'provincia_id' => '24', 'nombre' => 'Santa Cruz del Norte'),
            59 => array('id' => '3306', 'provincia_id' => '33', 'nombre' => 'Manzanillo'),
            60 => array('id' => '2507', 'provincia_id' => '25', 'nombre' => 'Pedro Betancourt'),
            61 => array('id' => '2908', 'provincia_id' => '29', 'nombre' => 'Ciego de Ávila'),
            62 => array('id' => '2315', 'provincia_id' => '23', 'nombre' => 'Cotorro'),
            63 => array('id' => '2103', 'provincia_id' => '21', 'nombre' => 'Minas de Matahambre'),
            64 => array('id' => '2201', 'provincia_id' => '22', 'nombre' => 'Bahía Honda'),
            65 => array('id' => '2801', 'provincia_id' => '28', 'nombre' => 'Yaguajay'),
            66 => array('id' => '3103', 'provincia_id' => '31', 'nombre' => 'Jesús Menéndez'),
            67 => array('id' => '2511', 'provincia_id' => '25', 'nombre' => 'Jagüey Grande'),
            68 => array('id' => '2410', 'provincia_id' => '24', 'nombre' => 'Batabanó'),
            69 => array('id' => '2102', 'provincia_id' => '21', 'nombre' => 'Mantua'),
            70 => array('id' => '2708', 'provincia_id' => '27', 'nombre' => 'Abreus'),
            71 => array('id' => '3501', 'provincia_id' => '35', 'nombre' => 'El Salvador'),
            72 => array('id' => '2403', 'provincia_id' => '24', 'nombre' => 'Jaruco'),
            73 => array('id' => '2312', 'provincia_id' => '23', 'nombre' => 'La Lisa'),
            74 => array('id' => '2610', 'provincia_id' => '26', 'nombre' => 'Cifuentes'),
            75 => array('id' => '2105', 'provincia_id' => '21', 'nombre' => 'La Palma'),
            76 => array('id' => '2314', 'provincia_id' => '23', 'nombre' => 'Arroyo Naranjo'),
            77 => array('id' => '3205', 'provincia_id' => '32', 'nombre' => 'Báguanos'),
            78 => array('id' => '3305', 'provincia_id' => '33', 'nombre' => 'Yara'),
            79 => array('id' => '2309', 'provincia_id' => '23', 'nombre' => 'Diez de Octubre'),
            80 => array('id' => '2303', 'provincia_id' => '23', 'nombre' => 'Centro Habana'),
            81 => array('id' => '3108', 'provincia_id' => '31', 'nombre' => 'Amancio Rodríguez'),
            82 => array('id' => '2503', 'provincia_id' => '25', 'nombre' => 'Martí'),
            83 => array('id' => '2308', 'provincia_id' => '23', 'nombre' => 'San Miguel del Padrón'),
            84 => array('id' => '3009', 'provincia_id' => '30', 'nombre' => 'Florida'),
            85 => array('id' => '2206', 'provincia_id' => '22', 'nombre' => 'San Antonio de los Baños'),
            86 => array('id' => '2502', 'provincia_id' => '25', 'nombre' => 'Cárdenas'),
            87 => array('id' => '3102', 'provincia_id' => '31', 'nombre' => 'Puerto Padre'),
            88 => array('id' => '2208', 'provincia_id' => '22', 'nombre' => 'Alquízar'),
            89 => array('id' => '3308', 'provincia_id' => '33', 'nombre' => 'Media Luna'),
            90 => array('id' => '3303', 'provincia_id' => '33', 'nombre' => 'Jiguaní'),
            91 => array('id' => '3503', 'provincia_id' => '35', 'nombre' => 'Yateras'),
            92 => array('id' => '3407', 'provincia_id' => '34', 'nombre' => 'Palma Soriano'),
            93 => array('id' => '2607', 'provincia_id' => '26', 'nombre' => 'Remedios'),
            94 => array('id' => '2608', 'provincia_id' => '26', 'nombre' => 'Placetas'),
            95 => array('id' => '3401', 'provincia_id' => '34', 'nombre' => 'Contramaestre'),
            96 => array('id' => '3313', 'provincia_id' => '33', 'nombre' => 'Guisa'),
            97 => array('id' => '2611', 'provincia_id' => '26', 'nombre' => 'Santo Domingo'),
            98 => array('id' => '2407', 'provincia_id' => '24', 'nombre' => 'San Nicolás'),
            99 => array('id' => '2803', 'provincia_id' => '28', 'nombre' => 'Taguasco'),
            100 => array('id' => '4001', 'provincia_id' => '40', 'nombre' => 'Isla de la Juventud'),
            101 => array('id' => '3213', 'provincia_id' => '32', 'nombre' => 'Sagua de Tánamo'),
            102 => array('id' => '2205', 'provincia_id' => '22', 'nombre' => 'Bauta'),
            103 => array('id' => '2906', 'provincia_id' => '29', 'nombre' => 'Florencia'),
            104 => array('id' => '2111', 'provincia_id' => '21', 'nombre' => 'Guane'),
            105 => array('id' => '2804', 'provincia_id' => '28', 'nombre' => 'Cabaiguán'),
            106 => array('id' => '3202', 'provincia_id' => '32', 'nombre' => 'Rafael Freyre'),
            107 => array('id' => '3402', 'provincia_id' => '34', 'nombre' => 'Julio Antonio Mella'),
            108 => array('id' => '3010', 'provincia_id' => '30', 'nombre' => 'Vertientes'),
            109 => array('id' => '2307', 'provincia_id' => '23', 'nombre' => 'Guanabacoa'),
            110 => array('id' => '3002', 'provincia_id' => '30', 'nombre' => 'Esmeralda'),
            111 => array('id' => '3311', 'provincia_id' => '33', 'nombre' => 'Bartolomé Masó'),
            112 => array('id' => '3405', 'provincia_id' => '34', 'nombre' => 'Songo La Maya'),
            113 => array('id' => '2406', 'provincia_id' => '24', 'nombre' => 'Nueva Paz'),
            114 => array('id' => '2110', 'provincia_id' => '21', 'nombre' => 'San Juan y Martínez'),
            115 => array('id' => '3212', 'provincia_id' => '32', 'nombre' => 'Frank País'),
            116 => array('id' => '2605', 'provincia_id' => '26', 'nombre' => 'Camajuaní'),
            117 => array('id' => '2109', 'provincia_id' => '21', 'nombre' => 'San Luis'),
            118 => array('id' => '2704', 'provincia_id' => '27', 'nombre' => 'Santa Isabel de las Lajas'),
            119 => array('id' => '3006', 'provincia_id' => '30', 'nombre' => 'Guáimaro'),
            120 => array('id' => '2802', 'provincia_id' => '28', 'nombre' => 'Jatibonico'),
            121 => array('id' => '2512', 'provincia_id' => '25', 'nombre' => 'Calimete'),
            122 => array('id' => '2408', 'provincia_id' => '24', 'nombre' => 'Güines'),
            123 => array('id' => '2506', 'provincia_id' => '25', 'nombre' => 'Jovellanos'),
            124 => array('id' => '2304', 'provincia_id' => '23', 'nombre' => 'La Habana Vieja'),
            125 => array('id' => '2204', 'provincia_id' => '22', 'nombre' => 'Caimito'),
            126 => array('id' => '2106', 'provincia_id' => '21', 'nombre' => 'Los Palacios'),
            127 => array('id' => '3309', 'provincia_id' => '33', 'nombre' => 'Niquero'),
            128 => array('id' => '2909', 'provincia_id' => '29', 'nombre' => 'Venezuela'),
            129 => array('id' => '3214', 'provincia_id' => '32', 'nombre' => 'Moa'),
            130 => array('id' => '3406', 'provincia_id' => '34', 'nombre' => 'Santiago de Cuba'),
            131 => array('id' => '3007', 'provincia_id' => '30', 'nombre' => 'Sibanicú'),
            132 => array('id' => '3207', 'provincia_id' => '32', 'nombre' => 'Calixto García'),
            133 => array('id' => '2306', 'provincia_id' => '23', 'nombre' => 'La Habana del Este'),
            134 => array('id' => '3307', 'provincia_id' => '33', 'nombre' => 'Campechuela'),
            135 => array('id' => '3203', 'provincia_id' => '32', 'nombre' => 'Banes'),
            136 => array('id' => '2805', 'provincia_id' => '28', 'nombre' => 'Fomento'),
            137 => array('id' => '2705', 'provincia_id' => '27', 'nombre' => 'Cruces'),
            138 => array('id' => '2508', 'provincia_id' => '25', 'nombre' => 'Limonar'),
            139 => array('id' => '3304', 'provincia_id' => '33', 'nombre' => 'Bayamo'),
            140 => array('id' => '2602', 'provincia_id' => '26', 'nombre' => 'Quemado de Güines'),
        );
    }


}
