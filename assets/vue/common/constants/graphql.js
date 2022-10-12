import gql from 'graphql-tag'

export const GENRE_WITH_GAMES_AND_DESCRIPTION = gql`
  query GetGenreWithGamesAndDescription($id: ID) {
  genre(ID: $id) {
      description
      games {
          edges {
              node {
                  id
                  name
                  headerImage
              }
          }
      }
  }
}`