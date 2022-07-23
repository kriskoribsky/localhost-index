# **localhost-index**
Multi-file project. This project is **continuation** (& main branch) of an original single-file project. It mimics the behaviour<br>
of Apache's ```autoindex``` module, which creates index of directory file listings. 
 
# Description
This project could be utilized by web developers developing in local environment (```localhost/```) to beautify default indexing.

## Features
- Eye-compelling minimalist design, good-looking on **mobile devices** too
- Custom icons for all major file extensions (e.g. ```.js, .php, .css, .html```, ...)
- Display of current ```system path``` & ```open in explorer``` button
- Sorting files according to ```file-size```, ```name``` and ```edit date```
- Quote viewer to motivate you

Icons used: [vscode-icons](https://github.com/vscode-icons/vscode-icons)<br>
File explorer ispired by Mac's [Finder](https://en.wikipedia.org/wiki/Finder_(software))

# Visuals

## Default localhost index without [FancyIndexing:](https://docstore.mik.ua/orelly/linux/apache/ch07_01.htm)
![localhost index, no FancyIndexing](docs/img/default-index1.png)

<br>

## Default localhost index with [FancyIndexing:](https://docstore.mik.ua/orelly/linux/apache/ch07_01.htm)
![localhost index with FancyIndexing](docs/img/default-index2.png)

# Setup
Place ```index.php``` file inside your local development folder (such as ```Sites```).<br>

<br>

Next time you open your ```http://localhost/``` there will display custom, formatted folders & files,<br>
as well as brief information about your working directory.

# To-do
- [ ] Test File->get_size() method on ```Windows``` (with ```com_dotnet```) extension enabled
- [ ] Test File->get_size() method on ```Linux```
- [ ] Test File->get_size() method on ```Mac OS```