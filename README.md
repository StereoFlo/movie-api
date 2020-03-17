# Проксирующе-кэшируещее API для TheMovieDatabase.org

### Доступные эндпойнты

##### Все тренды за неделю
GET /trending?page={page}

page - integer

#### Открыть информацию по фильму
GET /movie/{id}

#### Открыть информацию по телепередаче / сериалу
GET /tv/{id}

#### Изображения к фильму
GET /movie/{id}/images

#### Информация по актеру
GET /person/{id}

#### Поиск по фильмам
GET /search?query={query}&page={page}

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
