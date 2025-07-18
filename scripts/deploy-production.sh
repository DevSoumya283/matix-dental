echo "Compressing site..."
tar -czf matix_deployment.tar.gz --exclude=".git" --exclude=".vagrant" --exclude="*.sql" --exclude="uploads" . 2> /dev/null
echo "Uploading site..."
scp matix_deployment.tar.gz ec2-user@ec2-13-57-0-120.us-west-1.compute.amazonaws.com:/var/www/matix/
rm matix_deployment.tar.gz
echo "Connecting to remote server..."
ssh ec2-user@ec2-13-57-0-120.us-west-1.compute.amazonaws.com << \EndOfCommands
echo "Configuring remote deployment location..."
cd /var/www/matix/releases/
ls -t | tail -n +4 | xargs rm -r --
cd /var/www/matix/
releaseName="releases/`date +%s`"
mkdir $releaseName
cd $releaseName
echo "Extracting on remote server..."
tar -xzf ../../matix_deployment.tar.gz
ln -s ../../../uploads uploads
echo "Setting file permissions..."
chmod -R 777 /var/www/matix/$releaseName
cd ../..
echo "Linking to current deployment and cleanup..."
rm current
ln -s $releaseName current
rm matix_deployment.tar.gz
EndOfCommands
echo "Deployment successful..."
