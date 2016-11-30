# Installation

- Install the bundle via composer (see symfony docs for details)
- Register the bundle in your AppKernel.php (see symfony docs for details)
- Create a custom Geobit class, if needed (details see below)

The bundle comes with two Doctrine "Mapped Superclasses": Geobit and GeobitPlus. Additionally, it comes with a GeobitEntity class, which extends the Geobitplus mapped superclass and is a doctrine entity itself.

The Geobit superclass contains the following fields:
- Latitude
- Longitude
- generatedAt
- changedAt
- active (boolean flag)

These fileds are necessary for the geobitInterface classes to work.

The GeobitPlus superclass extends the Geobit superclass by the following fields:
- nickname
- countryCode
- administrativeArea
- city
- postalCode
- route
- formattedAddress
- comment
- type

These fileds are typically used with the ReverseGeocodingApi (see below) to store geocoding information.

To store the data in a DB via doctrine, a subclass must be created which extends one of the superclasses above. The bundle provides a subclass called GeobitEntity, which extends the Mapped Superclass GeobitPlus. If it suits your needs, you can use it as is.
Otherwise, just create your own entity class based on the GeobitEntity template. Note that in this case, the GeobitEntity class still creates its own DB table, which is then never used. If you want, you can just delete it from your DB or keep it there (empty).

```
use Doctrine\ORM\Mapping as ORM;

/**
 * GeobitEntity
 *
 * @ORM\Table(name="geobit")
 * @ORM\Entity(repositoryClass="Mallapp\GeobitsBundle\Repository\GeobitRepository")
 */
class GeobitEntity extends GeobitPlus
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
```


# Usage of the GeobitInterface

Use the `GeobitInterface` class to access the bundles functions. Create the interface class in your controller:

```
        $gInterface = GeobitInterface::create(
                $this->getDoctrine()->getRepository('MallappGeobitsBundle:GeobitEntity'),
                $this->getDoctrine()->getRepository('MallappGeobitsBundle:UserRetrieval'),
                $this->getDoctrine()->getManager()
                );
```

Note that you may have to provide the correct repository if you use your own Geobit Subclass instead of the GeobitEntity provided.

After creation, you can use the interface functions. The most important ones are:
- put(Geobit $geobit)
- setActive(Geobit $geobit, $active)
- getChangedGeobitsInRect(CoordinateBox $boundingBox, $forceReload, $userToken)
- ackGeobitRetrieval(CoordinateBox $boundingBox, $userToken, $retrievalDate)
- flushDB()

IMPORTANT: after every persistence call (e.g. put, setActive, ackGeobitRetrieval), you need to call `flushDB()` to persist the changes in the database. This allows you to first perform all the changes and then persist everything in one step.

# Usage of the ReverseGeocodingApi

Use the API class to call the static function:

```
$loc = ReverseGeocodingApi::getLocationFromCoordinate(47.385777, 8.500454);
```

Your return value in `$loc` contains a `SimpleLocation` object with the following fields:

```
public $latitude;
public $longitude;
public $nickname;
public $countryCode;
public $administrativeArea;
public $city;
public $postalCode;
public $route;
public $formattedAddress;
```

If you like, create a fully initialized `GeobitEntity` object by calling the factory function:
```
$geobit = GeobitEntity::createFromSimpleLocation($loc);
```
