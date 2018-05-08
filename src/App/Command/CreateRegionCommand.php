<?php

namespace App\Command;

use App\Entity\Region;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateRegionCommand extends Command
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('game:create-regions')
            ->setDescription('Creates Regions')
            ->setHelp('This command create a new Regions for the map')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Region Creator',
            '==============',
            '',
        ]);

        $regions = [
            [
                'name' => 'Les Gorges du Monde',
                'description' => 'Autrefois, un gouffre sans fond est apparu sans crier gare à l’endroit où se trouve maintenant l’Ile de la Rédemption. A cette époque, des milliers de monstres en sont sortis et ont déferlés sur le continent. Quand les armées du continent ont repoussé ces créatures et sont arrivés aux Gorges, ils sont parvenus à boucher le gouffre avec des monticules de terres qui forment aujourd’hui l’Ile de la Rédemption. Si aucune créature n’est jamais réapparue, beaucoup pensent que celles-ci se préparent à une nouvelle invasion. Ce n’est pas le cas des habitants de cette Ile fertile où il fait toutefois bon vivre.',
            ],
            [
                'name' => 'Terres dévastées d\'Ethnir',
                'description' => 'Cette contrée septentrionale est le vestige d’un cataclysme qui a autrefois bouleversé le monde. Une guerre entre les mages et des créatures d’un autre temps venue du Nord a ravagé cette terre, ne laissant après elle qu’une terre désolée. Les effluves de magie noir qui hantent encore ces lieux corrompent l’âme et absorbent l’essence vitale de tout être. La faune et la flore qui s’y développe depuis est emplie de magie ; on y croise des champignons géants et des créatures aussi dangereuses que magnifiques. Seuls les plus audacieux parviennent à survivre dans cette région d’un autre monde dans laquelle même le froid du Nord n’existe pas.',
            ],
            [
                'name' => 'Diaphleim',
                'description' => 'Une blancheur éternelle recouvre cette terre composée de banquise et de dunes enneigées dépourvues de vie. Ce territoire est fréquenté par tant de trolls des glaces et d’esprits damnés que peu de gens s’aventurent hors des quelques villages qui résistent aux tempêtes ravageuses. On raconte que les dieux eux-mêmes auraient maudit cet endroit pour le rendre impropre à toute exploitation.',
            ],
            [
                'name' => 'Terres de Givre',
                'description' => 'Une épaisse forêt recouvre cette région en permanence balayée par le froid. Cependant, l’abondance de bois et la protection au vent que les forêts permettent rendent cette région facile à vivre. Les villes des Terres de Givre sont peu nombreuses mais concentrent énormément de population. Les Barbares de Montagnes veillent à ce que les créatures dangereuses restent à distance des habitations et s’assurent de la sécurité des convois de marchandises qui traversent leur territoire.',
            ],
            [
                'name' => 'Montagnes Blanches',
                'description' => 'Malgré l’abrupté de ses pans et l’intensité des vents qui les balayent, les chemins qui parcourent cette chaine de montagne sont entretenus et praticables avec un minimum d’expérience. Si peu d’habitations s’implantes dans cette région, elle demeure un haut lieu de fréquentation commerciale. Seuls les chemins qui montent jusqu’aux sommets sont semés de dangers et de monstres nuisibles. Les plus audacieux des voyageurs tentent de rejoindre le Refuge des Adorateurs des Divins au cours d’un périple épique.',
            ],
            [
                'name' => 'Contrées de Ballum',
                'description' => 'Cet immense territoire montagneux parsemé de lacs et de rivières fourmille d’activités. Les eaux sont poissonneuses et les montagnes peuplées d’une faune sauvage peu hostile. Les habitants de cette contrée s’épanouissent avec une rare facilité autour de chaque point d’eau. Les citadelles les plus puissantes s’approprient généralement un lac et une colonne rocheuse qui leur garantissent les vivres et une connexion avec le reste du continent. Si les montagnes empêchent toute culture de prendre forme, celles-ci regorgent de minerais exploitables et très convoités.',
            ],
            [
                'name' => 'Plaines venteuses',
                'description' => 'Les Plaines venteuses sont constamment balayées par un vent chaud en provenance du désert proche. Ces terres fertiles n’ont jamais connu ni la guerre, ni la famine et elles approvisionnent la majeure partie du monde en blé et chevaux. Les plus grands propriétaires terriens possèdent des vallées entières destinées à la production de ressources qui seront ensuite vendues à Exodia ou par les caravaniers du désert.',
            ],
            [
                'name' => 'Fournaise de Nao\'oïtte',
                'description' => 'La chaleur de cette région brûle tout ce qui essaie de s’implanter ; végétaux comme animaux. Quelques rares bestiaux se terrent dans un sable brûlant. Les seuls voyageurs qui osent braver le climat sont des caravaniers qui vont et viennent entre sables mouvants et tempêtes de sable. On dit qu’il est impossible de traverser ce désert sans être accompagné par un de ces commerçants itinérants. Eux seuls connaissent les chemins surs, les dangers à éviter et l’emplacement des quelques rares refuges qui n’ont pas encore été ensevelis sous les dunes. La couleur claire et monochrome de cette région est néanmoins favorable à la recherche de pierres rares tombées du ciel.',
            ],
            [
                'name' => 'Oasis de Furibonde',
                'description' => 'Un regroupement de gisements aquifères inépuisables et le passage d’un fleuve gigantesque, le Furibonde, ont permis à la végétation de pousser malgré les conditions climatiques rudes. Sous une chaleur de plomb, une flore luxuriante se développe autour de plans d’eau d’une fraicheur appréciée de tous. C’est le point de passage tous ceux qui compte traverser le désert. Certains passionnés y vivent même toute l’année tant les conditions climatiques sont attrayantes et les victuailles abondantes.',
            ],
            [
                'name' => 'Var\' Umbir',
                'description' => 'Var’Umhir signifie littéralement « Lieu de vie » dans le langage Faël. Cette région à la population aussi sauvage que diversifiée peut sembler inhabitée aux premiers abords. En effet, ceux qui y ont élu domicile vivent à l’abri des regards, plongés dans leurs recherches et leurs expériences. C’est ainsi dans cette région reculée que les gobelins et les Faël ont décidé de se retrancher pour exercer leur art loin des soucis du reste du monde. Tout étranger est le bienvenu tant qu’il n’entrave pas le labeur des autochtones. Ce dernier peut alors apprécier le dépaysement du cadre peu commun de cette région unique. Si toutefois il parvient à rester hors de portée des bêtes sauvages qui y règnent en maitres.',
            ],
            [
                'name' => 'Monts Brumeux',
                'description' => 'Séparant les terres des gobelins et celles de Krulls, la chaine des Monts Embrumés est en permanence recouverte d’un épais brouillard. La magie noire qui hante ce lieu attire des créatures de l’ombre dont la noirceur assombrit l’air qu’elles respirent. Ainsi, vues de l’extérieur, ces montagnes donnent l’impression d’un gigantesque raz-de-marée grisâtre voilant l’horizon. Personne ne s’aventure dans ce récif montagneux dans lequel rôdent des forces occultes. On dit que sur les chemins qui le parcourent on peut entendre les cris de détresse de ceux qui ont chuté. Les adeptes de magie noire s’y rendent régulièrement pour se ressourcer.',
            ],
            [
                'name' => 'Contrées Bestiales',
                'description' => 'Cette région est communément appelée « Terre des Krulls ». La nomination « Krulls » désigne toutes les créatures semi-intelligentes qui sont regroupées dans ces contrées. Orcs, Trolls, Cyclopes… Si certaines sont civilisées et forment de véritables communautés, d’autres sont sauvages et peuvent faire preuve d’une violence inouïe. Encerclée par les Monts Embrumés au Nord, par l’Océan aux méridiens et par les Terres de Feu au Sud, cette région forme une prison naturelle qui permet de contenir toutes les créatures imprévisibles dépourvues de raison et de les isoler du reste du monde.',
            ],
            [
                'name' => 'Marais du Magister',
                'description' => 'Si le Lac Magister situé au centre des marécages est considéré comme l’une des plus grandes merveilles du monde, autant pour sa splendeur que pour la magie qui en émane, ce n’est pas le cas des marais qui l’entourent. Les crus du lac ont permis à ce dernier de sortir de son lit et se déverser sur les terres jusqu’à l’océan. Les essences magiques ont imprégné les plantes et les animaux de cette terre autrefois florissante, rendant les bêtes contaminées plus intelligentes et les minéraux doués de mouvements. Il n’est pas rare de croiser un golem de pierre inoffensif ou un bosquet doué de parole durant la traversée périlleuse de ces marais. Les quelques habitations se résument à des huttes sur pilotis accumulées les unes contre les autres. Les félins et autres canidés qui vivaient dans cette région se sont regroupés en différents clans et craignent par-dessus tous les habitants des Iles flottantes.',
            ],
            [
                'name' => 'Iles Royales',
                'description' => 'Cet archipel est principalement fréquenté par des pirates de toutes sortes qui écument les mers sans relâche. C’est une des rares régions où les valeurs n’ont aucune valeur et où tout est permis. Depuis leurs repaires, les capitaines les plus puissants gouvernent les différentes villes en vendant leurs butins et en assassinant leurs ennemis. L’archipel fourmille de coupe-gorges et autres vermines avides de richesses. L’alcool coule à flots de jour comme de nuit dans les ruelles de ces cités construites à partir des coques des navires abordés. Les filles de joie et les mercenaires fréquentent les flibustiers les plus riches dans cet univers hors du temps où même le plus jeune des enfants peut vous assassiner au détour d’un chemin. Les pirates ont leur propre gouvernement, et peuvent l’exercer selon leur bon vouloir tant qu’ils ne portent pas atteinte au continent.',
            ],
            [
                'name' => 'Bois de l\'Oublie',
                'description' => 'C’est ainsi qu’on nomme les différentes forêts qui recouvrent cette vaste région. Elles constituent le lieu de vie de deux créatures qui vivent malgré tout en parfaite harmonie. Les Sorcières de l’Anse sont officiellement encore traquées par l’Institution des Précepteurs, mais les seigneurs de l’ordre tolèrent leur présence nuisible tant qu’elle se limite au territoire des Elfes. Ces derniers, quant à eux, vivent avec une telle discrétion que même leurs voisines ne parviennent pas à leur nuire. Un puissant sortilège mis au point par la magie commune de ces deux peuples rend amnésique quiconque demeure trop longtemps dans le bois sans y avoir été invité. Si les sorcières anthropophages se font une joie d’accueillir les voyageurs abêtit dans leurs demeures, les elfes se contentent de les raccompagner à la lisière du bois.',
            ],
            [
                'name' => 'Creux du Flot',
                'description' => 'Des pans rocheux impressionnants s’élèvent vers le ciel dans cette région qui semble avoir subi un cataclysme divin. Si le terrain est particulièrement abrupt et peu enclin aux cultures de par ses vents forts et son climat imprévisible, c’est un lieu tranquille appréciés par nombre des habitants qui vivent entre ses falaises et ses crevasses. La terre du Creux produit, à ce qu’on dit, les meilleurs millésimes.',
            ],
            [
                'name' => 'Terres de Feu',
                'description' => 'Des rivières de lave coulent dans cette région recouverte de cendre et de roches volcaniques. Un vent d’est permanent balaye la chaine des Crache-Feu et pousse toutes les émanations volcaniques vers le sud du continent. Pluies acides et tempêtes de feu sont monnaies courantes dans cette région suffocante. Les cités qui ont la volonté de s’implanter dans cette région ont besoin de puissants mages pour recouvrir les habitations d’une alcôve protégeant de la chaleur et des débris tombés du ciel. Une fois la terre volcanique refroidie, elle constitue l’un des sols les plus fertiles qui soit. De dangereuses guêpes rouges peuvent être élevées pour procurer du miel et de la cire. Mais attention à leur piqure mortelle !',
            ],
            [
                'name' => 'Terres Immaculées',
                'description' => 'Encerclée par les terres de feu au Nord et par les océans sur ses pans, les Terres Immaculées portent ce nom en raison du contraste flagrant entre son climat et celui des régions voisines. De par sa localisation, ces terres sont très difficiles d’accès et représente une épreuve pour quiconque veut les atteindre. Les Terres Immaculées sont peuplées par une civilisation considérée comme parfaite, vivant de presque rien à travers une philosophie reposant sur la contemplation passive des phénomènes. Aucune créature maléfique ne vie dans cette région baigner d’une puissance réconfortante et d’un climat tempéré.',
            ],
            [
                'name' => 'Craches-Feu',
                'description' => 'La chaine des Craches-Feu est composée de volcans actifs de tailles variées. Lorsque le volcan le plus vieux s’éteint, un autre surgit de la mer en quelques centaines d’années. Beaucoup de légendes circulent sur la cause de ce phénomène mais personne n’a jamais pu plonger dans les eaux bouillantes qui entourent les Craches-Feu. Très peu d’animaux marins vivent dans ces rivages dans lesquelles des coulées de lave en fusions disparaissent tous les jours. Les volcans les moins actifs offrent malgré tous des ressources attrayantes. L’un des volcans les plus actifs est habité par les Adorateurs des Enfers qui disent avoir trouvé la porte qui mène à un autre monde.',
            ],
            [
                'name' => 'Les Damnides',
                'description' => 'Qui aurait un jour cru qu’une telle aberration pourrait exister. Sur cette terre maudite, le climat est soumis à des forces contradictoires phénoménales. Ainsi, d’un bord à l’autre d’une des rivières toxiques, l’air peut être incroyablement chaud ou d’une fraicheur agréable. Des trombes d’eau s’abattent sur un sol sec sous un soleil de plomb. A certains endroits de cette terre, l’humidité formées au sol s’élève vers le ciel de manière totalement inexpliquée. C’est une région pleine de contradiction où toute magie est impossible. Ceux qui s’aventure hors des habitations se perdent immanquablement parmi les environnement aléatoires et temporaires tant et si bien qu’ils perdent l’esprit en quelques heures. Les hauts criminel et les ennemis des Royaumes sont jetés en exil dans ces terres d’où aucun esprit saint ne revient.',
            ],
        ];

        foreach ($regions as $regionConfig) {
            $region = new Region();
            $region->setName($regionConfig['name']);
            $region->setDescription($regionConfig['description']);

            $this->em->persist($region);
        }

        $this->em->flush();

        $output->writeln('Les régions ont bien été enregistrées !');
    }
}
