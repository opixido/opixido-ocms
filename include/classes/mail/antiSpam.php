<?php

class antiSpam
{

    function genMailTo($email)
    {

        return '<a href="mailto:' . $email . '">' . $email . '</a>';
    }

}

