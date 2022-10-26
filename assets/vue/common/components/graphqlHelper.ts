import axios from "axios";
export default class GraphqlHelper {

    public static async queryPoster(query: string, variables: object): Promise<object> {
        const response = await axios.post("http://127.0.0.1:8000/api/graphql", {
                query: query,
                variables: variables,
            },
            {
                headers: {
                    'Content-Type': 'application/json'
                }
            }
        );

        return response.data.data;
    }
}