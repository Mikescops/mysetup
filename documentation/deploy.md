# How to deploy

First **tag a release** on Github.

```
ssh worker@mysetup.co -p 4444
*** enter worker password ***

su -u
*** enter root password ***

#### IF NEEDED
apt update
apt upgrade
####

su -l www-data -s /bin/bash

eval $(ssh-agent -s) && ssh-add ~/.ssh/mysetup_rsa
*** enter deploy ssh key passphrase ***

cd mysetup/

git fetch

bin/cake Setup.MaintenanceMode activate

git checkout vx.x.x

bash bin/deployment.sh

exit # x3
```

After this you can clear web cache from the Admin panel.
