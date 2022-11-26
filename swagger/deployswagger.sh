#!/bin/bash

#-------------------------------------------
# deployswagger.sh
#
# builds the swagger.yaml file into html and json into the 'upload' directory.
# deploys the contents of the 'upload' directory to the target serving host.
#
# there are two possible hosting strategies:
#   - ec2: static files served via nginx on an aws ec2
#   - s3: static files served via aws' s3 bucket web server
# choose the one that is correct for the serving strategy and configure below
#
# prerequisites:
#   for all deploy targets:
#       - nodejs and npm
#       - npx 
#       - php 7.2^
#       - the cloverhitchbot_mssg.php script located in ~/bin/ with permissions 755
#   for ec2:
#       - an ssh key that allows access for the ubuntu user
#       - firewall permissions for port 22 for your host box
#   for s3:
#       - the aws cli executable on your local box
#       - aws iam credentials in a named block in ~/.aws/credentials
#
# gbh

###
# one of 's3' or 'ec2'
#
deploytarget="ec2"

###
# name of the project
project_name="survivor"

###
# ec2 configurations
#
ssh_pem_path="/home/ghorwood/.sshprivate/survivor.pem.pem"
ec2_fqdn="documentation.dailysurvivorpool.com"

###
# s3 configurations
#
aws_binary="/usr/bin/aws"
s3_fqdn="survivor-swagger.fruitbat.systems"
aws_profile="fruitbat-sls"

###
# make sure openapi-generator-cli is installed
npm install

###
# reset the target 'upload' dir 
rm -rf upload
mkdir upload

###
# build the docs in the 'upload' directory
npx @openapitools/openapi-generator-cli generate -i ./swagger.yaml  -g openapi  -o upload
cp index.html upload/
#cp notes.html upload/

###
# upload to the ec2
if [ $deploytarget = "ec2" ]; then
    ssh-add $ssh_pem_path
    scp -r ./upload/* ubuntu@$ec2_fqdn:/var/www/html/documentation.survivor/
fi;

###
# upload to s3
if [ $deploytarget = "s3" ]; then
    $aws_binary s3 sync upload s3://$s3_fqdn  --profile $aws_profile
fi;

###
# notify slack
#~/bin/cloverhitchbot_mssg.php "Docs for $project_name updated"
