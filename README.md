# Проксирующе-кэшируещее API для TheMovieDatabase.org

### Доступные эндпойнты

##### /trending
Все тренды за неделю

#### /movie/{id}
Открыть информацию по фильму

#### /tv/{id}
Открыть информацию по телепередаче / сериалу

#### /movie/{id}/images
Изображения к фильму

#### /person/{id}
Информация по актеру

#### /search
Поиск по фильмам

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
