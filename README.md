# Install Certbot in PHP Server (Amazon Linux 2)
## Follow the below steps to install certificate on server
### Step 1 - Enable epel repository on server. It is not enabled by default.
```
amazon-linux-extras install epel -y
```
or
```
yum-config-manager --enable epel
```
### Step 2 - Install certbot packages on server
```
yum install certbot certbot-nginx -y
```
### Step 3 - Generate/Request the let's encrypt certificate
```
certbot --nginx -d smiansh.com
```