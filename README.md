**Install Redis**

<br>`sudo apt-get install redis-server` </p>
`sudo apt-get install php-redis`

`sudo nano /etc/redis/redis.conf` </p>
           <p> maxmemory 128mb  </p>
            maxmemory-policy allkeys-lru
            
`sudo systemctl enable redis-server.service` </p>
`sudo service apache2 restart`
