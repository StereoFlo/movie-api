# Проксирующе-кэшируещее API для TheMovieDatabase.org

### Доступные эндпойнты

##### Все тренды за неделю
**GET** _/trending?page={page}_

page - integer

#### Открыть информацию по фильму
**GET** _/movie/{id}_

#### Открыть информацию по телепередаче / сериалу
**GET** /tv/{id}

#### Изображения к фильму
**GET** _/movie/{id}/images_

#### Информация по актеру
**GET** _/person/{id}_

#### Поиск по фильмам
**GET** _/search?query={query}&page={page}_

query - string
page - integer

### Запуск

#### Конфигурация
скопировать файл

```bash
  cp .env.dist .env
```
Заполнить своими данными
```bash
TMDB_API_KEY=
TMDB_API_LANG=
```

#### Docker
```bash
  docker-compose up --build
```
#### без докера
ну просто запусти, тыжпрограммист
