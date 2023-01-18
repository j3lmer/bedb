import axios from "axios";
import base from "./base"

export default class GraphqlHelper {

    public static async queryPoster(query: string, variables: object): Promise<object> {
        const response = await axios.post(`${base.getBase()}api/graphql`, {
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