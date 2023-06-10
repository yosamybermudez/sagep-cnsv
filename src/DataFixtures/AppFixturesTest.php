<?php

namespace App\DataFixtures;

use App\Entity\Cargo;
use App\Entity\Encuentro;
use App\Entity\EncuentroAcuerdo;
use App\Entity\EncuentroAcuerdoTrazabilidad;
use App\Entity\EncuentroTipo;
use App\Entity\Rol;
use App\Entity\Trabajador;
use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixturesTest extends Fixture
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
        $faker = Factory::create();

        /*Cargos*/
        for($i = 0; $i < 50; $i++){
            $cargo = new Cargo();
            $cargo->setNombre($faker->jobTitle());
            $manager->persist($cargo);
        }
        $manager->flush();

        $cargos = $manager->getRepository(Cargo::class)->findAll();
        for($i = 0; $i < 50; $i++){
            $trabajador = new Trabajador();
            $trabajador->setNombres($faker->name());
            $trabajador->setApellidos($faker->lastName());
            $trabajador->setCarneIdentidad($faker->unique()->creditCardNumber());
            $trabajador->setCargo($cargos[rand(0,count($cargos)-1)]);
            $trabajador->setAlta(true);
            $manager->persist($trabajador);
        }
        $manager->flush();

        $tipos = array(['Reunión inicio de semana' => 'RIS', 'Reunión de vialidad' => 'RVI', 'Reunión general' => 'REG']);
        foreach (array_keys($tipos[0]) as $key){

            $tipo = new EncuentroTipo();
            $tipo->setNombre($key);
            $tipo->setAbreviatura($tipos[0][$key]);
            $manager->persist($tipo);
        }
        $manager->flush();

        /*Encuentros*/
        $fechas = $this->excelArray();
        foreach($fechas as $i => $fecha){
            $periodicidad = $fecha[0];
            $fecha = new \DateTime($fecha[1]);
            $encuentro = new Encuentro();

            if($periodicidad ==='semanal') $tipoEncuentro = $manager->getRepository(EncuentroTipo::class)->findOneByNombre('Reunión inicio de semana');
            elseif(in_array($periodicidad,['quincenal','mensual'])) $tipoEncuentro = $manager->getRepository(EncuentroTipo::class)->findOneByNombre('Reunión de vialidad');
            else $tipoEncuentro = $manager->getRepository(EncuentroTipo::class)->findOneByNombre('Reunión general');
            $encuentro->setTipoEncuentro($tipoEncuentro);

            $encuentro->setNombre('Encuentro ' . $periodicidad . " " . $fecha->format('d-m-Y'));
            $encuentro->setDescripcion($faker->realText());
            $encuentro->setRefEvento($this->nomencladorEncuentro($manager));
            $encuentro->setFechaEvento($fecha);
            $encuentro->setCantidadTrabajadores(1);
            $encuentro->setHora(new \DateTime('08:00:00'));
            $encuentro->setHoraFin(new \DateTime('10:00:00'));
            $encuentro->setLugar("Salón de reuniones # " . $faker->numberBetween(1,5));
            $encuentro->setDirigeEncuentro("Becerra");
            $encuentro->setEstado("A aprobación del presidente");
            $manager->persist($encuentro);
            $manager->flush();
        }

        /* Acuerdos */
        $encuentros = $manager->getRepository(Encuentro::class)->findAll();

        foreach ($encuentros as $enc){
            for($i = 0; $i <= $faker->numberBetween(4,7); $i++) {
                $acuerdo = new EncuentroAcuerdo();
                $acuerdo->setEncuentro($manager->getRepository(Encuentro::class)->find($enc->getId()));
                $acuerdo->setDescripcion($i+1 . " - " . $enc->getRefEvento() . " - " . $faker->realText(50));
                $acuerdo->setNoAcuerdo($this->nomencladorEncuentroAcuerdo($manager,$enc));
                $acuerdo->setFechaInicio($enc->getFechaEvento());


                $estado = $faker->randomElement(['Cumplido en parte', 'Cumplido', 'Pospuesto', 'Incumplido', 'Cancelado']);
                $periodicidadAcuerdo= $faker->randomElement(['No', 'Mensual', 'Quincenal', 'Mensual', 'Trimestral', 'Semestral', 'Anual']);

                $acuerdo->setPeriodicidad($periodicidadAcuerdo);

                $aux_array = array('semanal' => 7, 'quincenal' => 15, 'mensual' => 30, 'trimestral' => 90, 'semestral' => 180);
                $periodic = explode(" ", $enc->getNombre())[1];
               // $fechaCumplimientoAux = date_modify($acuerdo->getFechaInicio(), '+ ' . $aux_array[$periodic] . ' days');

                $manager->persist($acuerdo);
                $manager->flush();

                $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                $acuerdoTrazabilidad->setEstado('En tiempo');
                $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                $acuerdoTrazabilidad->setActivo(true); // Los acuerdos en tiempo estan activos siempre
                //$acuerdoTrazabilidad->setFechaRevision($fechaCumplimientoAux);
                $acuerdoTrazabilidad->setFechaModificacion($enc->getFechaEvento());

                switch ($estado){
                    case 'Cumplido' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Cumplido');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($enc);
                        $acuerdoTrazabilidad->setFechaModificacion($enc->getFechaEvento());

                        if($periodicidadAcuerdo === 'No'){
                            $acuerdoTrazabilidad->setObservaciones('Finalizado');
                            //$acuerdoTrazabilidad->setFechaRevision($enc->getFechaEvento());
                        } else {
                            $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                            $acuerdoTrazabilidad2->setEstado('En tiempo');
                            $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                            $acuerdoTrazabilidad2->setActivo(true);
                            $acuerdoTrazabilidad2->setFechaModificacion($enc->getFechaEvento());
                            $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $enc->getFechaEvento()));
                        }
                        break;}
                    case 'Cumplido en parte' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Cumplido en parte');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($enc);
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                       // $acuerdoTrazabilidad->setFechaRevision($enc->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($enc->getFechaEvento());


                        $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad2->setEstado('En tiempo');
                        $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad2->setActivo(true);
                        //if($periodicidad === 'No'){
                        $acuerdoTrazabilidad2->setFechaModificacion($enc->getFechaEvento());
                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $enc->getFechaEvento()));
//                    } else {
//                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), new \DateTime($acuerdoFechaCumplimiento)));
//                    }
                        break;}
                    case 'Pospuesto' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Pospuesto');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($enc);
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                        //$acuerdoTrazabilidad->setFechaRevision($enc->getFechaEvento());
                        $acuerdoTrazabilidad->setFechaModificacion($enc->getFechaEvento());

                        $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad2->setEstado('En tiempo');
                        $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad2->setActivo(true);
                        //   if($periodicidad === 'No'){
                        $acuerdoTrazabilidad2->setFechaModificacion($enc->getFechaEvento());
                        $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad() === 'No' ? 'Semanal' : $acuerdo->getPeriodicidad(), $enc->getFechaEvento()));
//                    } else {
//                        $acuerdoTrazabilidad->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $acuerdoFechaCumplimiento));
//                    }
                        break;}
                    case 'Incumplido' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Incumplido');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($enc);
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                        $acuerdoTrazabilidad->setFechaModificacion($enc->getFechaEvento());
                        //$acuerdoTrazabilidad->setFechaRevision($enc->getFechaEvento());

                        if($periodicidadAcuerdo !== 'No') {
                            $acuerdoTrazabilidad2 = new EncuentroAcuerdoTrazabilidad();
                            $acuerdoTrazabilidad2->setEstado('En tiempo');
                            $acuerdoTrazabilidad2->setAcuerdo($acuerdo);
                            $acuerdoTrazabilidad2->setActivo(true);
                            $acuerdoTrazabilidad2->setFechaRevision($this->acuerdoPeriodicidadHandler($acuerdo->getPeriodicidad(), $enc->getFechaEvento()));
                        }
                        break;}
                    case 'Cancelado' : {
                        $acuerdoTrazabilidad = new EncuentroAcuerdoTrazabilidad();
                        $acuerdoTrazabilidad->setEstado('Cancelado');
                        $acuerdoTrazabilidad->setAcuerdo($acuerdo);
                        $acuerdoTrazabilidad->setActivo(false);
                        $acuerdoTrazabilidad->setEncuentroModificador($enc);
                        $acuerdoTrazabilidad->setFechaModificacion($enc->getFechaEvento());
                        $acuerdoTrazabilidad->setObservaciones('Finalizado');
                        break;}
                    default : {
                        break;}
                }
                if(isset($acuerdoTrazabilidad)) { $manager->persist($acuerdoTrazabilidad); }
                if(isset($acuerdoTrazabilidad2)) { $manager->persist($acuerdoTrazabilidad2); }
                $manager->flush();
                }
        }
    }

    public function nomencladorEncuentro(ObjectManager $entityManager){

        $proximo_numero = count($entityManager->getRepository(Encuentro::class)->findByAnnoActual()) + 1;
        $cadena = 'ENC-CNSV-';

        if($proximo_numero < 10)
            $cadena .= "000" . $proximo_numero;
        if($proximo_numero >= 10 and $proximo_numero < 100)
            $cadena .= "00" . $proximo_numero;
        if($proximo_numero >= 100 and $proximo_numero < 1000)
            $cadena .= "0" . $proximo_numero;

        $cadena .= "-" . date("Y");
        return $cadena;
    }
    public function nomencladorEncuentroAcuerdo(ObjectManager $entityManager, Encuentro $encuentro){

        $proximo_numero = count($entityManager->getRepository(EncuentroAcuerdo::class)->findAcuerdosByTipoEncuentro($encuentro->getTipoEncuentro())) + 1;
        $consecutivo = '';
        if($proximo_numero < 10)
            $consecutivo .= "000" . $proximo_numero;
        if($proximo_numero >= 10 and $proximo_numero < 100)
            $consecutivo .= "00" . $proximo_numero;
        if($proximo_numero >= 100 and $proximo_numero < 1000)
            $consecutivo .= "0" . $proximo_numero;

        $cadena = implode("-",[
            'AC',
            $encuentro->getTipoEncuentro()->getAbreviatura(),
            $consecutivo,
            date("Y")
        ]);
        return $cadena;
    }
    public function excelArray(){
        return array(
            0 => array('semanal', '03-01-2022'),
            1 => array('quincenal', '04-01-2022'),
            2 => array('mensual', '05-01-2022'),
            3 => array('trimestral', '06-01-2022'),
            4 => array('semestral', '07-01-2022'),
            5 => array('semanal', '10-01-2022'),
            6 => array('semanal', '17-01-2022'),
            7 => array('quincenal', '19-01-2022'),
            8 => array('semanal', '24-01-2022'),
            9 => array('semanal', '31-01-2022'),
            10 => array('quincenal', '03-02-2022'),
            11 => array('mensual', '04-02-2022'),
            12 => array('semanal', '07-02-2022'),
            13 => array('semanal', '14-02-2022'),
            14 => array('quincenal', '18-02-2022'),
            15 => array('semanal', '21-02-2022'),
            16 => array('semanal', '28-02-2022'),
            17 => array('quincenal', '05-03-2022'),
            18 => array('mensual', '06-03-2022'),
            19 => array('semanal', '07-03-2022'),
            20 => array('semanal', '14-03-2022'),
            21 => array('quincenal', '20-03-2022'),
            22 => array('semanal', '21-03-2022'),
            23 => array('semanal', '28-03-2022'),
            24 => array('semanal', '04-04-2022'),
            25 => array('quincenal', '04-04-2022'),
            26 => array('mensual', '05-04-2022'),
            27 => array('trimestral', '06-04-2022'),
            28 => array('semanal', '11-04-2022'),
            29 => array('semanal', '18-04-2022'),
            30 => array('quincenal', '19-04-2022'),
            31 => array('semanal', '25-04-2022'),
        );
    }
    private function acuerdoPeriodicidadHandler(string $periodicidad, \DateTime $fecha) : \DateTime{
    $fecha = clone $fecha;
    switch ($periodicidad){
        case 'Mensual': {
            $fecha = date_modify($fecha,"+30 days");
            break;
        }
        case 'Quincenal': {
            $fecha = date_modify($fecha,"+15 days");
            break;
        }
        case 'Trimestral': {
            $fecha = date_modify($fecha,"+90 days");
            break;
        }
        case 'Semestral': {
            $fecha = date_modify($fecha,"+180 days");
            break;
        }
        case 'Anual': {
            $fecha = date_modify($fecha,"+357 days");
            break;
        }
        default: break;
    }
    if(date("w", $fecha->getTimestamp()) === "0"){
        $fecha = date_modify($fecha,"+1 day");
    }
    if(date("w", $fecha->getTimestamp()) === "6"){
        $fecha = date_modify($fecha,"+2 day");
    }
    return $fecha;
}

}
