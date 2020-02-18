<?php
/*error_reporting(E_ALL);
ini_set("display_errors", 1);*/

define('ENVIRONMENT', "production");
define('PATH_LOGS', "");
define('URL_TRANSACTION', "");
define('devID', "35eeac4e-6006-41d9-adee-c4becc11ba46");
define('appID', "TUFFComp-CloudCom-PRD-1ab9522e2-1a463403");
define('certID', "PRD-ab9522e246d1-1a9b-4fc5-b633-aad6");
define('Server', "https://api.ebay.com/ws/api.dll");
define('ruName', "TUFF_Company-TUFFComp-CloudC-qjqlrnfod");
define('Authorization', "VFVGRkNvbXAtQ2xvdWRDb20tUFJELTFhYjk1MjJlMi0xYTQ2MzQwMzpQUkQtYWI5NTIyZTI0NmQxLTFhOWItNGZjNS1iNjMzLWFhZDY=");





define('userToken', "AgAAAA**AQAAAA**aAAAAA**zGSqWA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFkoGjC5SBpQmdj6x9nY+seQ**FUwDAA**AAMAAA**fH9Q/KR6cvLHrieYU/y91ISc/thXLlX1gXB6UEL7BcUpcIsv00KK+9msaAO5k0qTSdq284XJ5xqTkkBe9bn6OZ94V9Nncb8PoRububB5ryf4bwgmUFgmjNCX79AswEaaRGs50GWMrTZrCU+kmrU5UFTtLIBNmk11Dg2Q2BZ3NyPdTwnCosQfWTmHr3/35/24hTdstuGPajRlMkMgvhVyhePw5O57a90bjJheidmJuVmMuLGsEhojSLzWrD4YccIeDPy69OM33nmWe3vsKTdRVI998aXXsomIH9tFA2FamN6c18HZCB99ANxhPnqAf5LX6AChVG/IDfRAbekYOC7Ci+fLOPAZEmkU49YQVijW2PPfm34y/qzS9QdZQUAggzVGblZmsHNO06dSnrsYPImvf9hhCiw3yNbpLpN+Bj/bDtALn5rb5Q4fa4kCDJ7UJ1FDG7vH/9XsyqQssELtmVMGPN0pBU92+nAbKWWyLy/PIojS2fhzHa+d0qqqLrhZ3MR/EsiQ6FHPh6VCynxba1ZeTza6lY+TVxqr16tLUaaIJlZVJVGTGZnUYKGlaTx6a7jhQQWEur2R2RDA5ymIecXfxGqOgSWVjI4N/jzYlxhAZFQJ691LaRv9652Jp6XXC+nDO2OGqGUTF8AVeLPG1iSa2a8G8cvPj/UHciKTUtiWkUqMqmd9/ERzQ83XhpQycorMJi30hOv/L9e3EOAIGnCjtJTUtpjBzof71yO1nYfr+ecCtNCqb/MaeoeRwdr5Sxpe");

define('TT', "v^1.1#i^1#I^3#f^0#r^0#p^1#t^H4sIAAAAAAAAAOVYfWwURRTv9kvLh4ZvUxXqAqGItzdzt3e923AXj2tLL0Bbetdq29Rmbne2t3Rvd92Z43r/NY2BCDbIh/5BIqmEYFT8gAB+xVRFEw2NCmKC/icBUzDGCDHiH0Z3r6VcK2mRXrCJl2xu582bN+/9fu/NzA7oLS17dFvdtt/nMvcUDvSC3kKGgbNBWWnJmvuKCstLCkCOAjPQu6K3uK9oeC1BSdUQmjAxdI3gip6kqhEhKwywKVMTdEQUImgoiYlARSEa2rRRcHFAMEyd6qKushWR6gDrF31enkeuuBfKvC/usqTaDZsxPcDGIfTxEvJC0e/zuSXZ6ickhSMaoUijAdYFoN8BoQO6YxAIHijwgPP5YBtb0YJNouiapcIBNph1V8iONXN8ndxVRAg2qWWEDUZCtdGGUKS6pj621pljKziKQ5QimiLjW2FdwhUtSE3hyachWW0hmhJFTAjrDI7MMN6oELrhzB24n4Va9vnjkkcGQJakKszH8wJlrW4mEZ3cD1uiSA45qypgjSo0MxWiFhrxLViko616y0SkusL+25xCqiIr2AywNetCraHGRjYYa66tDetJwxFW9ZRkvTkam6odEMX9HpcLu6w33uvmgXt0ohFrozBPmCmsa5Jig0Yq6nW6Dlte44nY8DnYWEoNWoMZkqntUa6e6waGVd42m9QRFlM0odm84qQFREW2OTUDY6MpNZV4iuIxCxM7shAFWGQYisRO7Mzm4mj69JAAm6DUEJzOdDrNpd2cbnY5XQBA55ObNkbFBE4i1tK1a31EX5l6gEPJhiJiayRRBJoxLF96rFy1HNC62KALeoHPM4r7eLeCE6X/EOTE7BxfEfmqEBHKfhm7qpD18FV+lI8KCY4mqdP2A8dRxpFEZjemhopE7BCtPEslsalIgtsju9w+GTskr1928H5ZdsQ9ktcBZYwBxvG4tQL+nwrldlM9ikUT07zket7yvK4mmYj49PWJRFuL1pVolOQwiiajzVuoT+G7692bm2Iin5IJ3xwK3G413Dp4UTdwo64qYiYPCNi1nkcU3KbUiEyaiWJVtQTTCpTYgc4sku3xxDKADIWzC5sT9aRTR9aKbos6sx5PK+aQYUSSyRRFcRVH8rOa/0cr+S3DU6yzzoyKyeJvhEhFGjmkcFk2ObJV5ExM9JRpnc+4BnvPjundWLNWQGrqqorNFjhtou82v3atT4HHv9ws7iz2/J1UZlJui6pipVDnTIvsrjCqoBm2G0NPldvLu9wATCuucJbTWGam7UN1OqFYmiy04vV3eKx2jv/IDxZkf7CPOQH6mKOFDAOcYCVcDh4pLWouLppTThSKOQXJHFG6NOvb1cRcN84YSDELSxllz9nt3+ZcKwx0gAfGLhbKiuDsnFsG8NDNnhJ4/5K50A8hdEPggTxoA8tv9hbDxcULzz++4eT2zlOXX3tiZ/viva8fesksOQTmjikxTElBcR9TMO+y0Kb+dJTDy9cMf1pzbX3l4XqxPRbc176kvb+DOWH8sAss/PLN0AeDZ6rF1S+KH135pPCVTanDncefO73os4MbW98ILM3MOmCAI/dWlh8f/n7pRefwr198WKkfqO6v+WMNbdo6+PGPe06vfkEYfGqlz1zVfO7SsfRRpqe9/9CC1vnvX79wYcjJ071n53TsuXD1zwXPqAu/Y377ej9I7DhWd/2b1nXBYfTzL5+/XLcLzeq4dvDSyf6qt967UvLX6YfP1Hr87qKnBx70VB6MkCNX958639b21e4V6VeH0tvOXFoy1PdYBJad27Ds7aYdz8LuRHhVemjR8x2DJZXz3nl3Wfm12fMv7uzb3dw0Qt/f21IpRvARAAA=");

/*private $production  = true;   // toggle to true if going against production
private $compatLevel = 991;    // eBay API version

private $devID = '35eeac4e-6006-41d9-adee-c4becc11ba46';   // these prod keys are different from sandbox keys
private $appID = 'TUFFComp-CloudCom-PRD-1ab9522e2-1a463403';
private $certID = 'PRD-ab9522e246d1-1a9b-4fc5-b633-aad6';
//set the Server to use (Sandbox or Production)
private $serverUrl = 'https://api.ebay.com/ws/api.dll';      // server URL different for prod and sandbox

//the token representing the eBay user to assign the call with
//run name
private $ruName = 'TUFF_Company-TUFFComp-CloudC-qjqlrnfod';*/

?>