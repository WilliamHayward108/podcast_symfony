
# Podcast Symfony Backend

This is my backend code for the podcast tech test.




## Usage/Examples




## Installation

The project uses docker with a php 7.4 build, an nginx config and a mysql (8.0) database.

Inside the root folder execute:

```bash
  docker-compose up -d --build
```

Then, inside the docker container execute:

```bash
  composer install
``` 
Inside the docker container execute:   
```bash
  symfony console make:migration
``` 
Inside the docker container execute: 
```bash
  symfony console doctrine:fixtures:load
``` 
The project should not successfuly be set up and accessible at localhost


## API Reference

#### Get statistics for episode by period

```http
  GET /getEpisodeStatistics/{uuid}/{period}
```

| Parameter | Type     | Description                      |
| :-------- | :------- | :-------------------------       |
| `uuid`    | `string` | uuid in string format of episode |
| `period` | `int` | day period to retrieve stats from      |


#### Download episode

```http
  GET /episodeDownloaded/{uuid}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `uuid`    | `string` | uuid in string format of episode to be downloaded|




## Design Decisions

Controller:

I decided to combine both a standard controller and the event system. I wanted to mimic a real system that I assumed would be generating requests from some kind of front end.
I decided based from the spec there should be two routes, one for downloading and one to retrieve the statistics. The download route is self explanatory, the statistics route I chose to add a date period field (defaults to 7 days) which will increase the potential data set returned. I decided to return an array of date->value as I thought this would be the relevant information the api would have to return to generate a chart, any other information I assumed would already exist within the frontend in a real world system so I just focused on the data for a chart.

Event:

Events are generated by an initial call to the episode download route. An event is then created with the episode information that has been retireved by doctrine for the given episode uuid. I decided to use an event subscriber as I have not had much experience using them so it seemed like a good opportunity to try it. 

## Expanding on the project

- Due to the scope of the test and the time constraints I chose to keep the entities to their most important components, in a real world system the enitites would have been built out with considerably more properties (such as episode duration, filesize etc)

- In a real system I would have built out the entity repositories a lot more, there is a lot of very helpful functionality that could be written for statistics based on podcasts rather than just episodes, same for the episode downloaded table

## Challenges

- While I have used docker before, I have never set up a docker compose or a docker file for a brand new project. While I didn't have too much trouble getting it up and running, I am sure there are better ways to handle the containers, I ended up with a strange file structure that I'm not the biggest fan of.

- Uuids are something that I have worked with before, but never in symfony. The Uid component was brand new to me, it wasn't difficult to understand but I struggled finding documentation on it within the symfony docs.



