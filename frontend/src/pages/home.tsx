import React, { useState, useEffect } from 'react';
import IPage from '../interfaces/page';
import axios from "axios";
import Grid from '@mui/material/Grid';
import Paper from '@mui/material/Paper';
import {LeagueReadDbDto} from "../api/dto/league.read.db.dto";
import Typography from "@mui/material/Typography";
import Box from "@mui/material/Box";
import Button from "@mui/material/Button";
import {LeagueReadRemoteDto} from "../api/dto/league.read.remote.dto";

const HomePage: React.FunctionComponent<IPage> = props => {
    const [league, setLeague] = useState([]);
    const [data, setData] = useState<number[]>([]);

    useEffect(() => {
        fetchLeagues();
    }, [data]);
    useEffect(() => {
        fetchLeaguesFromDb();
    }, []);

    const options = {
        method: 'GET',
        url: 'https://api-football-v1.p.rapidapi.com/v3/leagues',
        params: {last: 20, country: "Italy"},
        headers: {
            'X-RapidAPI-Key': '135095f978mshb2636255937cdabp1d14fdjsnebebfad817c1',
            'X-RapidAPI-Host': 'api-football-v1.p.rapidapi.com'
        }
    };
    const fetchLeagues = () => {
        axios.request(options).then(function (response) {
            setLeague(response.data.response)
        }).catch(function (error) {
            console.error(error);
        });
    };

    const fetchLeaguesFromDb = () => {
        axios.request({
            method: 'GET',
            url: 'http://127.0.0.1:8447/getFavourite',
        }).then(function (response) {
            response.data.map((league: LeagueReadDbDto)=>{
                setData((data) => {
                    return [...data, league.idRemote]
                })
            })
        }).catch(function (error) {
            console.error(error);
        });
    };

    const addToFavourite = (league: any) => {
        axios.request({
            method: 'POST',
            url: 'http://127.0.0.1:8447/addFavourite',
            data: {...league},
        })
    };
    return <div className='item-container'>
        {
            league.map((league:LeagueReadRemoteDto) => (
            <Paper key={league.league.id}
                   sx={{
                       p: 2,
                       margin: 10,
                       maxWidth: 1500,
                       flexGrow: 1,
                   }}
            >
                <Grid container spacing={2} className='grid-container'>
                    <Grid item xs container direction="column" spacing={1}>
                        <Grid item xs >
                            <Typography  variant="body2" color="text.secondary" paddingTop={5} fontSize={40}>League</Typography>
                            <Typography  variant="body2" color="text.secondary" paddingTop={5} fontSize={20}>Name: {league.league.name}</Typography>
                            <Box
                                component="img"
                                sx={{
                                    height: 100,
                                    width: 100,
                                    maxHeight: { xs: 92, md: 100 },
                                    maxWidth: { xs: 82, md: 85 },
                                }}
                                alt="Image"
                                src={league.league.logo}
                            />
                            <Typography  variant="body2" color="text.secondary" paddingTop={5} fontSize={40}>Country</Typography>
                            <Box
                                component="img"
                                sx={{
                                    height: 30,
                                    width: 30,
                                    maxHeight: { xs: 92, md: 100 },
                                    maxWidth: { xs: 82, md: 85 },
                                }}
                                alt="Image"
                                src={league.country.flag}
                            />
                            <Typography  variant="body2" color="text.secondary" paddingTop={5} fontSize={40}></Typography>
                            {data.includes(league.league.id) ?
                                <div className='added'>Added</div>:
                                <Button className='button' variant="contained" color="success" onClick={
                                    async () => {
                                        setData((data) => {
                                            return [...data, league.league.id]
                                        })
                                        await addToFavourite({
                                            "id_remote": league.league.id,
                                            "name":league.league.name,
                                            "type":league.league.type,
                                            "logo": league.league.logo,
                                            "country": league.country.name,
                                            "flag":league.country.flag})
                                    }
                                }>
                                Add to favourite
                                </Button>}
                        </Grid>
                    </Grid>
                </Grid>
            </Paper>
        ))}
    </div>
}

export default HomePage;