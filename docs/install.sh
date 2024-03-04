#! /bin/bash

echo "Installing libnss3-tools..."
##############################
sudo apt install libnss3-tools
##############################
if [[ $? -eq 0 ]] 
then
	echo -e "\nOK\n"
else
	echo -e "\nERROR: something went wrong...\n"
	exit -1
fi

echo "Installing Homebrew..."
###############################################################################################
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
###############################################################################################
if [[ $? -eq 0 ]] 
then
	echo -e "\nOK\n"
else
	echo -e "\nERROR: something went wrong...\n"
	exit -1
fi

echo "Adding Homebrew to your PATH..."
###############################################################################################
(echo; echo 'eval "$(/home/linuxbrew/.linuxbrew/bin/brew shellenv)"') >> /home/federico/.bashrc
###############################################################################################
if ! [[ $? -eq 0 ]] 
then
	echo -e "\nERROR: something went wrong...\n"
	exit -1
fi
######################################################
eval "$(/home/linuxbrew/.linuxbrew/bin/brew shellenv)"
######################################################
if [[ $? -eq 0 ]] 
then
	echo -e "\nOK\n"
else
	echo -e "\nERROR: something went wrong...\n"
	exit -1
fi


echo "Installing mkcert..."
###################
brew install mkcert
###################
if [[ $? -eq 0 ]] 
then
	echo -e "\nOK\n"
else
	echo -e "\nERROR: something went wrong...\n"
	exit -1
fi

echo -n "Are you using Firefox-based browsers? [Yes/No]?"
read

if [[ $REPLY == "Yes" ]] || [[ $REPLY == "Y" ]] || [[ $REPLY == "yes" ]] || [[ $REPLY == "y" ]]
then
	brew install nss
fi

echo "Installing CA..."
###################
mkcert -install
###################
if [[ $? -eq 0 ]] 
then
	echo -e "\nOK\n"
else
	echo -e "\nERROR: something went wrong...\n"
	exit -1
fi

echo "Generating server certificate..."
#########################
cd ../docker/apache-conf/
mkcert localhost
########################
if [[ $? -eq 0 ]]
then
        echo -e "\nOK\n"
	exit 0
else
        echo -e "\nERROR: something went wrong...\n"
        exit -1
fi
