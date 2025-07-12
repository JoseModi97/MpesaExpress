#!/bin/bash
sudo cp etc/apache2/sites-available/mpesa.conf /etc/apache2/sites-available/mpesa.conf
sudo a2ensite mpesa.conf
sudo a2dissite 000-default.conf
sudo service apache2 restart
