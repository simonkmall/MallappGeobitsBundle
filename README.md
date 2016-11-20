# Installation

- Install the bundle via composer (see symfony docs for details)
- Register the bundle in your AppKernel.php (see symfony docs for details)
- Create a custom Geobit class, if needed (details see below)

The bundle comes with a Geobit class, which is a Doctrine "Mapped Superclass" and not an entity itself.

The Geobit superclass contains the following fields:
- Latitude
- Longitude
- Generation DateTime (called generatedAt)
- Last Change DateTime (called changedAt)
- Active flag (boolean)

Additionally, the bundle provides a subclass called GeobitEntity, which extends the Mapped Superclass and is a doctrine entity.

The GeobitEntity additionally contains an optional (i.e. nullable) field:
- Comment

If those fields are not enough, you have to create your own subclass extending the Geobit Mapped Superclass.

You have to options to do so:
- Create your own class. Just use the GeobitEntity as a starting point and customize as you need. YOU NEED TO SPECIFY YOUR OWN TABLE NAME (instead of "geobit"). Doctrine will still create the geobit table, but it will not be used.
- Or, override the GeobitEntity class. You can again use the class as your starting point, and don't have to specify your own table name.

```
use Doctrine\ORM\Mapping as ORM;

/**
 * GeobitEntity
 *
 * @ORM\Table(name="geobit")
 * @ORM\Entity(repositoryClass="Mallapp\GeobitsBundle\Repository\GeobitRepository")
 */
class GeobitEntity extends Geobit
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
     * @var string
     *
     * @ORM\Column(name="comment", type="string", nullable=true)
     */
    private $comment;


    public function getAsArray() {
        
        $returnArray = Array();
        
        $returnArray['id'] = $this->id;
        $returnArray['latitude'] = $this->latitude;
        $returnArray['longitude'] = $this->longitude;
        $returnArray['comment'] = $this->comment;

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    
    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Geobit
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        
        $this->changedAt = new \DateTime();

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }


    
}
```

Note that you need to implement the `getAsArray` function, it is used to convert the object into a json object transmitted via the API.

# Usage

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


