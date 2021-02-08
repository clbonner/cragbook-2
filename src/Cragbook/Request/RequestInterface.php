namespace Cragbook\Request;

interface RequestInterface {
    private $data;

    // Gets data from the database
    public getData($method, $id);

    // Returns data as JSON encoded string
    public getJSON();
}