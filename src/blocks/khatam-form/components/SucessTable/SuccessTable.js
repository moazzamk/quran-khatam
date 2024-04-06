import {
  Table,
  TableHead,
  TableBody,
  TableRow,
  TableCell,
  Paper,
} from '@mui/material';

import styles from './styles';

export default function SuccessTable ({users}) {
  return (
    <Paper elevation={2} sx={ styles } >
      <Table>
        <TableHead>
          <TableRow>
            <TableCell>Juz</TableCell>
            <TableCell>Reciter</TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
          { users.map(user =>
            <TableRow>
              <TableCell>{ user.juz }</TableCell>
              <TableCell>{ `${user.name.firstName} ${user.name.lastName}` }</TableCell>
            </TableRow>
          )}
        </TableBody>
      </Table>
    </Paper>
  );
}
