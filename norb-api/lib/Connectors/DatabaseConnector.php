<?php

namespace norb_api\Connectors;

abstract class DatabaseConnector
{
    //maybe also add flair , so that sql and other database connectors can be added and table pattern thingies can adopt to differnt databases
     abstract public function getConnection();
}
