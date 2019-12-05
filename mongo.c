int main(int argc, char **argv)
{ 

    auto client = mongoc_client_new ("mongodb+srv://uiucrenter:<uiucrenter>@uiucrenter-gimsv.mongodb.net/test?retryWrites=true&w=majority");
    auto db = mongoc_client_get_database (client, "test");

    return 0;
}
