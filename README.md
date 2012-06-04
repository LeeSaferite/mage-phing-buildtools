Magento phing build tools

This repo should live as a sub-module of a lightweight Magento project

It needs the following properties to be defined to run correctly:

	project.name - The name of the project. This is used for some dir creation
	project.deploydir - This the the dir where your project should deploy
	project.magento_base - This it the location the Magento archive should be extracted into
	project.magento_archive - This is a full path to a tarball containing Magento (the root inside the archive is ignored)
	
This is purely for my personal use an has not been tested on other setups.
You are welcome to use it as you see fit but you are on your own if it breaks things.
